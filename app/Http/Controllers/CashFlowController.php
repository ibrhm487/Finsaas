<?php

namespace App\Http\Controllers;

use App\Models\CashFlowReport;
use App\Services\Reporting\CashFlowReportEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashFlowController extends Controller
{
    protected $engine;

    public function __construct(CashFlowReportEngine $engine)
    {
        $this->engine = $engine;
    }

    public function index()
    {
        $companyId = auth()->user()->company_id;

        $reports = CashFlowReport::where('company_id', $companyId)
            ->latest('generated_at')
            ->get();

        return view('cash-flows.index', compact('reports'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'method' => 'required|in:direct,indirect',
        ]);

        $companyId = auth()->user()->company_id;
        $userId = auth()->id();

        $report = $this->engine->generate(
            $companyId,
            $request->from,
            $request->to,
            $request->method,
            $userId
        );

        return redirect()
            ->route('cash-flows.show', $report->id)
            ->with('success', 'Laporan arus kas berhasil dibuat.');
    }

    public function show(CashFlowReport $cashFlow)
    {
        if ($cashFlow->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('cash-flows.show', compact('cashFlow'));
    }

    public function regenerate(Request $request, CashFlowReport $cashFlow)
    {
        if ($cashFlow->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $newReport = $this->engine->generate(
            $cashFlow->company_id,
            $cashFlow->periode_start,
            $cashFlow->periode_end,
            $cashFlow->method,
            auth()->id()
        );

        return redirect()
            ->route('cash-flows.show', $newReport->id)
            ->with('success', 'Laporan arus kas berhasil diperbarui.');
    }
}
