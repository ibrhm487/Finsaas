<?php

namespace App\Services\Reporting;

use App\Models\Account;
use App\Models\CashFlowMapping;
use App\Models\JournalDetail;
use App\Models\JournalHeader;
use Illuminate\Support\Facades\DB;

class CashFlowIndirectService
{
    public function generate($companyId, $startDate, $endDate)
    {
        $mappings = CashFlowMapping::where('company_id', $companyId)
            ->where('method', 'indirect')
            ->get();

        $journalIds = JournalHeader::where('company_id', $companyId)
            ->where('status', 'posted')
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->pluck('id');

        $allDetails = JournalDetail::whereIn('journal_header_id', $journalIds)
            ->with('akun')
            ->get();

        $cashAccountIds = DB::table('cash_accounts')
            ->where('company_id', $companyId)
            ->pluck('account_id');

        $netIncome = $this->calculateNetIncome($allDetails, $mappings);
        $nonCashAdjustments = $this->calculateNonCashAdjustments($allDetails, $mappings);
        $workingCapitalChanges = $this->calculateWorkingCapitalChanges($companyId, $startDate, $endDate, $mappings);

        $operatingNet = $netIncome + $nonCashAdjustments + $workingCapitalChanges;

        $investing = $this->calculateCashTransactions($allDetails, $cashAccountIds, $mappings, 'investing');
        $financing = $this->calculateCashTransactions($allDetails, $cashAccountIds, $mappings, 'financing');

        $netInvesting = $investing['net'];
        $netFinancing = $financing['net'];

        return [
            'operating' => [
                'net_income' => $netIncome,
                'non_cash_adjustments' => $nonCashAdjustments,
                'working_capital_changes' => $workingCapitalChanges,
                'non_cash_items' => $this->getNonCashItems($allDetails, $mappings),
                'working_capital_items' => $this->getWorkingCapitalItems($companyId, $startDate, $endDate, $mappings),
                'items' => [],
                'total_inflow' => 0,
                'total_outflow' => 0,
                'net' => $operatingNet,
            ],
            'investing' => $investing,
            'financing' => $financing,
            'net_increase' => $operatingNet + $netInvesting + $netFinancing,
        ];
    }

    private function calculateNetIncome($allDetails, $mappings)
    {
        $accountIds = $mappings->where('method', 'indirect')->pluck('account_id');
        $accounts = \App\Models\Account::whereIn('id', $accountIds)->get()->keyBy('id');

        $operatingIds = $mappings->where('method', 'indirect')
            ->where('activity', 'operating')
            ->pluck('account_id');

        $pendapatanTotal = 0;
        $bebanTotal = 0;

        foreach ($allDetails as $d) {
            if (!$operatingIds->contains($d->akun_id)) continue;
            $account = $accounts->get($d->akun_id);
            if (!$account) continue;

            if ($account->kategori === 'Pendapatan' && $d->kredit > 0) {
                $pendapatanTotal += $d->kredit;
            }
            if ($account->kategori === 'Beban' && $d->debit > 0) {
                $bebanTotal += $d->debit;
            }
        }

        return $pendapatanTotal - $bebanTotal;
    }

    private function calculateNonCashAdjustments($allDetails, $mappings)
    {
        $nonCashIds = $mappings->where('is_non_cash', true)
            ->where('method', 'indirect')
            ->pluck('account_id');

        return $allDetails->whereIn('akun_id', $nonCashIds)
            ->where('debit', '>', 0)
            ->sum('debit');
    }

    private function calculateWorkingCapitalChanges($companyId, $startDate, $endDate, $mappings)
    {
        $allIds = $mappings->where('method', 'indirect')
            ->where('activity', 'operating')
            ->pluck('account_id');

        $accounts = \App\Models\Account::whereIn('id', $allIds)->get();

        $operatingAssetIds = $accounts->filter(fn($a) => $a->kategori === 'Aset')->pluck('id');
        $operatingLiabilityIds = $accounts->filter(fn($a) => $a->kategori === 'Kewajiban')->pluck('id');

        $changeAsset = $this->calculateBalanceChange($companyId, $operatingAssetIds, $startDate, $endDate, 'debit');
        $changeLiability = $this->calculateBalanceChange($companyId, $operatingLiabilityIds, $startDate, $endDate, 'kredit');

        return -$changeAsset + $changeLiability;
    }

    private function calculateBalanceChange($companyId, $accountIds, $startDate, $endDate, $normalBalance)
    {
        if ($accountIds->isEmpty()) return 0;

        $journalIds = JournalHeader::where('company_id', $companyId)
            ->where('status', 'posted')
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->pluck('id');

        $totalDebit = JournalDetail::whereIn('journal_header_id', $journalIds)
            ->whereIn('akun_id', $accountIds)
            ->sum('debit');

        $totalKredit = JournalDetail::whereIn('journal_header_id', $journalIds)
            ->whereIn('akun_id', $accountIds)
            ->sum('kredit');

        return $normalBalance === 'debit' ? $totalDebit - $totalKredit : $totalKredit - $totalDebit;
    }

    private function calculateCashTransactions($allDetails, $cashAccountIds, $mappings, $activity)
    {
        $activityAccountIds = $mappings->where('activity', $activity)
            ->where('method', 'indirect')
            ->pluck('account_id');

        $items = [];
        $totalInflow = 0;
        $totalOutflow = 0;

        $journalWithCash = $allDetails->whereIn('akun_id', $cashAccountIds)
            ->pluck('journal_header_id')
            ->unique();

        $nonCashDetails = $allDetails->whereIn('journal_header_id', $journalWithCash);

        foreach ($nonCashDetails as $d) {
            if (!$activityAccountIds->contains($d->akun_id)) continue;
            if ($d->kredit > 0) {
                $items[] = [
                    'category' => $activity,
                    'account_name' => $d->akun->nama_akun ?? '-',
                    'amount' => $d->kredit,
                    'type' => 'inflow',
                ];
                $totalInflow += $d->kredit;
            }
            if ($d->debit > 0) {
                $items[] = [
                    'category' => $activity,
                    'account_name' => $d->akun->nama_akun ?? '-',
                    'amount' => $d->debit,
                    'type' => 'outflow',
                ];
                $totalOutflow += $d->debit;
            }
        }

        return [
            'items' => $items,
            'total_inflow' => $totalInflow,
            'total_outflow' => $totalOutflow,
            'net' => $totalInflow - $totalOutflow,
        ];
    }

    private function getNonCashItems($allDetails, $mappings)
    {
        $nonCashIds = $mappings->where('is_non_cash', true)
            ->where('method', 'indirect')
            ->pluck('account_id');

        return $allDetails->whereIn('akun_id', $nonCashIds)
            ->where('debit', '>', 0)
            ->groupBy('akun_id')
            ->map(fn($items, $akunId) => [
                'account_name' => $items->first()->akun->nama_akun ?? '-',
                'amount' => $items->sum('debit'),
            ])
            ->values()
            ->toArray();
    }

    private function getWorkingCapitalItems($companyId, $startDate, $endDate, $mappings)
    {
        $items = [];

        $assetIds = $mappings->where('activity', 'operating')
            ->where('method', 'indirect')
            ->where('is_inflow', false)
            ->where('is_non_cash', false)
            ->pluck('account_id');

        $liabilityIds = $mappings->where('activity', 'operating')
            ->where('method', 'indirect')
            ->where('is_inflow', true)
            ->where('is_non_cash', false)
            ->pluck('account_id');

        foreach ($assetIds as $id) {
            $change = $this->calculateBalanceChange($companyId, collect([$id]), $startDate, $endDate, 'debit');
            if ($change != 0) {
                $account = Account::find($id);
                $items[] = [
                    'account_name' => $account->nama_akun ?? '-',
                    'change' => -$change,
                ];
            }
        }

        foreach ($liabilityIds as $id) {
            $change = $this->calculateBalanceChange($companyId, collect([$id]), $startDate, $endDate, 'kredit');
            if ($change != 0) {
                $account = Account::find($id);
                $items[] = [
                    'account_name' => $account->nama_akun ?? '-',
                    'change' => $change,
                ];
            }
        }

        return $items;
    }
}
