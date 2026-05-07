<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\RepaymentController;
use App\Http\Controllers\BorrowerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/view', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/repayments', [RepaymentController::class, 'index'])->name('repayments.index');
    Route::get('/borrowers', [BorrowerController::class, 'index'])->name('borrowers.index');
    Route::view('/reports', 'reports.index')->name('reports.index');
});

require __DIR__.'/auth.php';
