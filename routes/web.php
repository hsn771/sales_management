<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\MonthlySummaryController;
use App\Http\Controllers\DeletionLogController;
use App\Http\Controllers\TargetController;

// Login
Route::get('/',      [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',[AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// Legacy URL → main app entry
Route::redirect('/dashboard', '/targets');

Route::get('/monthly-summary', [MonthlySummaryController::class, 'index'])->name('monthly-summary.index');

Route::get('/daily-report', [DailyReportController::class, 'index'])->name('daily-report.index');

Route::get('/deletion-log', [DeletionLogController::class, 'index'])->name('deletion-log.index');

// Targets CRUD
Route::get('targets/rodes', [TargetController::class, 'indexRodes'])->name('targets.rodes.index');
Route::delete('targets/rodes/{rode}', [TargetController::class, 'destroyRode'])->name('targets.rodes.destroy');
Route::get('targets/srs', [TargetController::class, 'indexSRs'])->name('targets.srs.index');
Route::delete('targets/srs/{sr}', [TargetController::class, 'destroySR'])->name('targets.srs.destroy');
Route::get('targets/create-rode', [TargetController::class, 'createRode'])->name('targets.createRode');
Route::get('targets/create-sr', [TargetController::class, 'createSR'])->name('targets.createSR');
Route::post('targets/store-rode', [TargetController::class, 'storeRode'])->name('targets.storeRode');
Route::post('targets/store-sr', [TargetController::class, 'storeSR'])->name('targets.storeSR');
Route::post('targets/report-lines', [TargetController::class, 'storeReportLine'])->name('targets.reportLines.store');
Route::post('targets/report-lines/reorder', [TargetController::class, 'reorderReportLines'])->name('targets.reportLines.reorder');
Route::delete('targets/report-lines/{reportLine}', [TargetController::class, 'destroyReportLine'])->name('targets.reportLines.destroy');
Route::post('targets/report-lines/{reportLine}/meta', [TargetController::class, 'updateReportLineMeta'])->name('targets.reportLines.updateMeta');
Route::post('targets/daily-upsert', [TargetController::class, 'upsertDailyTarget'])->name('targets.dailyUpsert');
Route::post('targets/{target}/inline-update', [TargetController::class, 'inlineUpdate'])->name('targets.inlineUpdate');
Route::post('targets/update-daily-cost', [TargetController::class, 'updateDailyCost'])->name('targets.updateDailyCost');
Route::post('targets/update-percent-base', [TargetController::class, 'updatePercentBase'])->name('targets.updatePercentBase');
Route::post('targets/update-apr-percent', [TargetController::class, 'updateAprPercent'])->name('targets.updateAprPercent');
Route::resource('targets', TargetController::class);
