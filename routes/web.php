<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\IncomeStatementController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');

    Route::resource('accounts', AccountController::class)->middleware('role:admin,akuntan');
    Route::resource('journals', JournalController::class)->middleware('role:admin,akuntan');
    Route::resource('users', UserController::class)->middleware('role:admin');

    Route::get('/company/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company', [CompanyController::class, 'update'])->name('company.update');

    Route::get('/general-ledgers', [GeneralLedgerController::class, 'index'])->name('general-ledgers.index');
    Route::get('/trial-balances', [TrialBalanceController::class, 'index'])->name('trial-balances.index');
    Route::get('/income-statements', [IncomeStatementController::class, 'index'])->name('income-statements.index');
    Route::get('/balance-sheets', [BalanceSheetController::class, 'index'])->name('balance-sheets.index');
    Route::get('/cash-flows', [CashFlowController::class, 'index'])->name('cash-flows.index');
    Route::post('/cash-flows/generate', [CashFlowController::class, 'generate'])->name('cash-flows.generate');
    Route::get('/cash-flows/{cashFlow}', [CashFlowController::class, 'show'])->name('cash-flows.show');
    Route::post('/cash-flows/{cashFlow}/regenerate', [CashFlowController::class, 'regenerate'])->name('cash-flows.regenerate');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index')->middleware('role:admin');

    Route::get('/exports/journal/{journal}/pdf', [ExportController::class, 'journalPdf'])->name('exports.journal.pdf');
    Route::get('/exports/journal/{journal}/excel', [ExportController::class, 'journalExcel'])->name('exports.journal.excel');
    Route::get('/exports/general-ledger/pdf', [ExportController::class, 'generalLedgerPdf'])->name('exports.general-ledger.pdf');
    Route::get('/exports/trial-balance/pdf', [ExportController::class, 'trialBalancePdf'])->name('exports.trial-balance.pdf');
    Route::get('/exports/trial-balance/excel', [ExportController::class, 'trialBalanceExcel'])->name('exports.trial-balance.excel');
    Route::get('/exports/income-statement/pdf', [ExportController::class, 'incomeStatementPdf'])->name('exports.income-statement.pdf');
    Route::get('/exports/income-statement/excel', [ExportController::class, 'incomeStatementExcel'])->name('exports.income-statement.excel');
    Route::get('/exports/balance-sheet/pdf', [ExportController::class, 'balanceSheetPdf'])->name('exports.balance-sheet.pdf');
    Route::get('/exports/balance-sheet/excel', [ExportController::class, 'balanceSheetExcel'])->name('exports.balance-sheet.excel');
    Route::get('/exports/cash-flow/pdf', [ExportController::class, 'cashFlowPdf'])->name('exports.cash-flow.pdf');
    Route::get('/exports/cash-flow/excel', [ExportController::class, 'cashFlowExcel'])->name('exports.cash-flow.excel');
});

require __DIR__.'/auth.php';
