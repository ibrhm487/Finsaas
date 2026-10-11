<?php

namespace App\Services\Reporting;

use App\Models\CashFlowReport;
use App\Models\JournalDetail;
use App\Models\JournalHeader;
use Illuminate\Support\Facades\DB;

class CashFlowReportEngine
{
    protected $directService;
    protected $indirectService;

    public function __construct(
        CashFlowDirectService $directService,
        CashFlowIndirectService $indirectService
    ) {
        $this->directService = $directService;
        $this->indirectService = $indirectService;
    }

    public function generate($companyId, $startDate, $endDate, $method, $userId)
    {
        $data = $method === 'indirect'
            ? $this->indirectService->generate($companyId, $startDate, $endDate)
            : $this->directService->generate($companyId, $startDate, $endDate);

        $beginningCash = $this->calculateBeginningCash($companyId, $startDate);
        $endingCash = $this->calculateEndingCash($companyId, $endDate);

        $validatedData = $this->validate($data, $beginningCash, $endingCash);

        $report = CashFlowReport::updateOrCreate(
            [
                'company_id' => $companyId,
                'periode_start' => $startDate,
                'periode_end' => $endDate,
                'method' => $method,
            ],
            [
                'operating_activities' => $validatedData['operating'],
                'investing_activities' => $validatedData['investing'],
                'financing_activities' => $validatedData['financing'],
                'net_operating' => $validatedData['operating']['net'] ?? 0,
                'net_investing' => $validatedData['investing']['net'] ?? 0,
                'net_financing' => $validatedData['financing']['net'] ?? 0,
                'net_increase' => $validatedData['net_increase'],
                'beginning_cash' => $beginningCash,
                'ending_cash' => $endingCash,
                'generated_by' => $userId,
                'generated_at' => now(),
            ]
        );

        return $report->fresh();
    }

    public function validate($data, $beginningCash, $endingCash)
    {
        $netIncrease = $data['net_increase'];

        $calculatedEnding = $beginningCash + $netIncrease;

        $data['net_increase'] = $netIncrease;
        $data['beginning_cash'] = $beginningCash;
        $data['ending_cash'] = $calculatedEnding;
        $data['validation'] = [
            'is_balanced' => abs($calculatedEnding - $endingCash) < 0.01,
            'expected_ending' => $endingCash,
            'calculated_ending' => $calculatedEnding,
        ];

        return $data;
    }

    public function calculateBeginningCash($companyId, $startDate)
    {
        $cashAccountIds = DB::table('cash_accounts')
            ->where('company_id', $companyId)
            ->pluck('account_id');

        if ($cashAccountIds->isEmpty()) return 0;

        $saldo = 0;

        $allBefore = JournalHeader::where('company_id', $companyId)
            ->where('status', 'posted')
            ->whereDate('tanggal', '<', $startDate)
            ->pluck('id');

        $totalDebitBefore = JournalDetail::whereIn('journal_header_id', $allBefore)
            ->whereIn('akun_id', $cashAccountIds)
            ->sum('debit');

        $totalKreditBefore = JournalDetail::whereIn('journal_header_id', $allBefore)
            ->whereIn('akun_id', $cashAccountIds)
            ->sum('kredit');

        return $totalDebitBefore - $totalKreditBefore;
    }

    public function calculateEndingCash($companyId, $endDate)
    {
        $cashAccountIds = DB::table('cash_accounts')
            ->where('company_id', $companyId)
            ->pluck('account_id');

        if ($cashAccountIds->isEmpty()) return 0;

        $allUpTo = JournalHeader::where('company_id', $companyId)
            ->where('status', 'posted')
            ->whereDate('tanggal', '<=', $endDate)
            ->pluck('id');

        $totalDebit = JournalDetail::whereIn('journal_header_id', $allUpTo)
            ->whereIn('akun_id', $cashAccountIds)
            ->sum('debit');

        $totalKredit = JournalDetail::whereIn('journal_header_id', $allUpTo)
            ->whereIn('akun_id', $cashAccountIds)
            ->sum('kredit');

        return $totalDebit - $totalKredit;
    }
}
