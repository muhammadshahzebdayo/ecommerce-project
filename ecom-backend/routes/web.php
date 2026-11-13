<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayrollSlipController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payroll/{payroll}/slip', [PayrollSlipController::class, 'view'])->name('payroll.slip');
Route::get('/payroll/{payroll}/slip/word', [PayrollSlipController::class, 'downloadWord'])->name('payroll.slip.word');
