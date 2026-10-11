<?php

namespace App\Services\Reporting;

use App\Models\CashFlowMapping;
use App\Models\JournalDetail;
use App\Models\JournalHeader;
use Illuminate\Support\Facades\DB;

class CashFlowDirectService
{
    public function generate($companyId, $startDate, $endDate)
    {
        $cashAccountIds = DB::table('cash_accounts')
            ->where('company_id', $companyId)
            ->pluck('account_id');

        if ($cashAccountIds->isEmpty()) {
            return $this->emptyResult();
        }

        $mappings = CashFlowMapping::where('company_id', $companyId)
            ->where('method', 'direct')
            ->get()
            ->keyBy('account_id');

        $operating = [];
        $investing = [];
        $financing = [];

        $journalIds = JournalHeader::where('company_id', $companyId)
            ->where('status', 'posted')
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->pluck('id');

        $cashEntries = JournalDetail::with('akun', 'journalHeader')
            ->whereIn('journal_header_id', $journalIds)
            ->whereIn('akun_id', $cashAccountIds)
            ->get()
            ->groupBy('journal_header_id');

        foreach ($cashEntries as $headerId => $entries) {
            $counterpartEntries = JournalDetail::with('akun', 'journalHeader')
                ->where('journal_header_id', $headerId)
                ->whereNotIn('akun_id', $cashAccountIds)
                ->get();

            foreach ($entries as $cashEntry) {
                $amount = 0;
                $isInflow = false;

                if ($cashEntry->debit > 0) {
                    $amount = $cashEntry->debit;
                    $isInflow = true;
                } else {
                    $amount = $cashEntry->kredit;
                    $isInflow = false;
                }

                if ($amount == 0) continue;

                foreach ($counterpartEntries as $ce) {
                    $mapping = $mappings->get($ce->akun_id);
                    if (!$mapping) continue;

                    $category = $mapping->cash_flow_category;
                    $activity = $mapping->activity;

                    $item = [
                        'category' => $category,
                        'account_name' => $ce->akun->nama_akun ?? '-',
                        'amount' => $amount,
                        'type' => $isInflow ? 'inflow' : 'outflow',
                        'date' => $ce->journalHeader->tanggal ?? null,
                    ];

                    match ($activity) {
                        'investing' => $investing[] = $item,
                        'financing' => $financing[] = $item,
                        default => $operating[] = $item,
                    };
                }
            }
        }

        return $this->buildResult($operating, $investing, $financing);
    }

    private function buildResult($operating, $investing, $financing)
    {
        $netOp = collect($operating)->sum(fn($i) => $i['type'] === 'inflow' ? $i['amount'] : -$i['amount']);
        $netInv = collect($investing)->sum(fn($i) => $i['type'] === 'inflow' ? $i['amount'] : -$i['amount']);
        $netFin = collect($financing)->sum(fn($i) => $i['type'] === 'inflow' ? $i['amount'] : -$i['amount']);

        return [
            'operating' => [
                'items' => $operating,
                'total_inflow' => collect($operating)->where('type', 'inflow')->sum('amount'),
                'total_outflow' => collect($operating)->where('type', 'outflow')->sum('amount'),
                'net' => $netOp,
            ],
            'investing' => [
                'items' => $investing,
                'total_inflow' => collect($investing)->where('type', 'inflow')->sum('amount'),
                'total_outflow' => collect($investing)->where('type', 'outflow')->sum('amount'),
                'net' => $netInv,
            ],
            'financing' => [
                'items' => $financing,
                'total_inflow' => collect($financing)->where('type', 'inflow')->sum('amount'),
                'total_outflow' => collect($financing)->where('type', 'outflow')->sum('amount'),
                'net' => $netFin,
            ],
            'net_increase' => $netOp + $netInv + $netFin,
        ];
    }

    private function emptyResult()
    {
        return [
            'operating' => ['items' => [], 'total_inflow' => 0, 'total_outflow' => 0, 'net' => 0],
            'investing' => ['items' => [], 'total_inflow' => 0, 'total_outflow' => 0, 'net' => 0],
            'financing' => ['items' => [], 'total_inflow' => 0, 'total_outflow' => 0, 'net' => 0],
            'net_increase' => 0,
        ];
    }
}
