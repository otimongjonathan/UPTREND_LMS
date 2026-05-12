<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\RepaymentController;
use App\Http\Controllers\RepaymentScheduleController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\LoanGuarantorController;
use App\Http\Controllers\CollateralController;
use App\Http\Controllers\LoanDisbursementController;
use App\Http\Controllers\CreditScoringController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\LoanProductController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\HomeController as CustomerHomeController;
use App\Http\Controllers\Customer\LoanController as CustomerLoanController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\SettingsController as CustomerSettingsController;
use App\Http\Controllers\LoanModificationController;
use App\Http\Controllers\LoanTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth:staff', 'verified'])->name('dashboard');

Route::middleware(['auth:staff'])->group(function () {
    Route::get('/profile/view', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::post('/loans/{loan}/issue', [LoanController::class, 'issue'])->name('loans.issue');
    Route::get('/loans/{loan}/download/{field}', [LoanController::class, 'downloadDocument'])->name('loans.download');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
    Route::post('/applications/{application}/update-terms', [ApplicationController::class, 'updateTerms'])->name('applications.update-terms');
    Route::post('/applications/{application}/calculate-fees', [ApplicationController::class, 'calculateFees'])->name('applications.calculate-fees');
    Route::post('/applications/{application}/assign-product', [ApplicationController::class, 'assignProduct'])->name('applications.assign-product');

    Route::get('/repayments', [RepaymentController::class, 'index'])->name('repayments.index');
    Route::get('/repayments/create', [RepaymentController::class, 'create'])->name('repayments.create');
    Route::post('/repayments', [RepaymentController::class, 'store'])->name('repayments.store');
    Route::get('/repayments/{repayment}', [RepaymentController::class, 'show'])->name('repayments.show');
    Route::get('/repayments/{repayment}/edit', [RepaymentController::class, 'edit'])->name('repayments.edit');
    Route::patch('/repayments/{repayment}', [RepaymentController::class, 'update'])->name('repayments.update');
    Route::post('/repayments/{repayment}/record-payment', [RepaymentController::class, 'recordPayment'])->name('repayments.record');
    Route::delete('/repayments/{repayment}', [RepaymentController::class, 'destroy'])->name('repayments.destroy');

    Route::get('/borrowers', [BorrowerController::class, 'index'])->name('borrowers.index');
    Route::get('/borrowers/{borrower}', [BorrowerController::class, 'show'])->name('borrowers.show');

    // Staff Management
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    
    // Loan Products
    Route::get('/loan-products', [LoanProductController::class, 'index'])->name('loan-products.index');
    Route::get('/loan-products/create', [LoanProductController::class, 'create'])->name('loan-products.create');
    Route::post('/loan-products', [LoanProductController::class, 'store'])->name('loan-products.store');
    Route::get('/loan-products/{loanProduct}/edit', [LoanProductController::class, 'edit'])->name('loan-products.edit');
    Route::put('/loan-products/{loanProduct}', [LoanProductController::class, 'update'])->name('loan-products.update');
    Route::delete('/loan-products/{loanProduct}', [LoanProductController::class, 'destroy'])->name('loan-products.destroy');
    Route::post('/loan-products/{loanProduct}/toggle', [LoanProductController::class, 'toggle'])->name('loan-products.toggle');
    
    // Guarantors
    Route::get('/loans/{loan}/guarantors', [LoanGuarantorController::class, 'index'])->name('guarantors.index');
    Route::get('/loans/{loan}/guarantors/create', [LoanGuarantorController::class, 'create'])->name('guarantors.create');
    Route::post('/loans/{loan}/guarantors', [LoanGuarantorController::class, 'store'])->name('guarantors.store');
    Route::get('/guarantors/{guarantor}/edit', [LoanGuarantorController::class, 'edit'])->name('guarantors.edit');
    Route::patch('/guarantors/{guarantor}', [LoanGuarantorController::class, 'update'])->name('guarantors.update');
    Route::delete('/guarantors/{guarantor}', [LoanGuarantorController::class, 'destroy'])->name('guarantors.destroy');
    Route::post('/guarantors/{guarantor}/approve', [LoanGuarantorController::class, 'approve'])->name('guarantors.approve');
    Route::post('/guarantors/{guarantor}/reject', [LoanGuarantorController::class, 'reject'])->name('guarantors.reject');
    
    // Collaterals
    Route::get('/loans/{loan}/collaterals', [CollateralController::class, 'index'])->name('collaterals.index');
    Route::get('/loans/{loan}/collaterals/create', [CollateralController::class, 'create'])->name('collaterals.create');
    Route::post('/loans/{loan}/collaterals', [CollateralController::class, 'store'])->name('collaterals.store');
    Route::get('/collaterals/{collateral}/edit', [CollateralController::class, 'edit'])->name('collaterals.edit');
    Route::patch('/collaterals/{collateral}', [CollateralController::class, 'update'])->name('collaterals.update');
    Route::delete('/collaterals/{collateral}', [CollateralController::class, 'destroy'])->name('collaterals.destroy');
    Route::post('/collaterals/{collateral}/verify', [CollateralController::class, 'verify'])->name('collaterals.verify');
    Route::post('/collaterals/{collateral}/reject', [CollateralController::class, 'reject'])->name('collaterals.reject');
    
    // Disbursements
    Route::get('/disbursements', [LoanDisbursementController::class, 'index'])->name('disbursements.index');
    Route::get('/loans/{loan}/disbursements/create', [LoanDisbursementController::class, 'create'])->name('disbursements.create');
    Route::post('/loans/{loan}/disbursements', [LoanDisbursementController::class, 'store'])->name('disbursements.store');
    Route::get('/disbursements/{disbursement}', [LoanDisbursementController::class, 'show'])->name('disbursements.show');
    Route::get('/disbursements/{disbursement}/edit', [LoanDisbursementController::class, 'edit'])->name('disbursements.edit');
    Route::patch('/disbursements/{disbursement}', [LoanDisbursementController::class, 'update'])->name('disbursements.update');
    Route::post('/disbursements/{disbursement}/approve', [LoanDisbursementController::class, 'approve'])->name('disbursements.approve');
    Route::post('/disbursements/{disbursement}/disburse', [LoanDisbursementController::class, 'disburse'])->name('disbursements.disburse');
    Route::post('/disbursements/{disbursement}/verify', [LoanDisbursementController::class, 'verify'])->name('disbursements.verify');
    Route::post('/disbursements/{disbursement}/cancel', [LoanDisbursementController::class, 'cancel'])->name('disbursements.cancel');
    
    // Repayment Schedules
    Route::get('/repayment-schedules', [RepaymentScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/repayment-schedules/{schedule}', [RepaymentScheduleController::class, 'show'])->name('schedules.show');
    Route::post('/repayment-schedules/{schedule}/payment', [RepaymentScheduleController::class, 'recordPayment'])->name('schedules.payment');
    Route::post('/loans/{loan}/regenerate-schedule', [RepaymentScheduleController::class, 'regenerate'])->name('schedules.regenerate');
    
    // Repayment Collection - TEMPORARILY DISABLED
    // Route::get('/loans/{loan}/repayments/collection', [RepaymentCollectionController::class, 'index'])->name('repayments.collection');
    // Route::get('/repayment-schedules/{schedule}/pay', [RepaymentCollectionController::class, 'create'])->name('repayments.pay');
    // Route::post('/repayment-schedules/{schedule}/payment', [RepaymentCollectionController::class, 'store'])->name('repayments.store');
    // Route::get('/repayment-schedules/{schedule}/edit', [RepaymentCollectionController::class, 'edit'])->name('repayments.edit-schedule');
    // Route::patch('/repayment-schedules/{schedule}', [RepaymentCollectionController::class, 'update'])->name('repayments.update-schedule');
    // Route::get('/loans/{loan}/payment-history', [RepaymentCollectionController::class, 'paymentHistory'])->name('repayments.payment-history');
    // Route::get('/payment-receipts/{receipt}', [RepaymentCollectionController::class, 'viewReceipt'])->name('repayments.receipt');
    // Route::post('/repayment-schedules/{schedule}/send-reminder', [RepaymentCollectionController::class, 'sendReminder'])->name('repayments.send-reminder');
    // Route::get('/repayments/bulk-report', [RepaymentCollectionController::class, 'bulkPaymentReport'])->name('repayments.bulk-report');
    
    // Credit Scoring
    Route::get('/credit-scores', [CreditScoringController::class, 'index'])->name('credit-scores.index');
    Route::get('/credit-scores/{creditScore}', [CreditScoringController::class, 'show'])->name('credit-scores.show');
    Route::post('/users/{user}/calculate-credit-score', [CreditScoringController::class, 'calculate'])->name('credit-scores.calculate');
    Route::post('/credit-scores/recalculate-all', [CreditScoringController::class, 'recalculateAll'])->name('credit-scores.recalculate-all');
    Route::get('/credit-scores/export', [CreditScoringController::class, 'export'])->name('credit-scores.export');
    
    // Loan Modifications
    Route::get('/loans/{loan}/modifications', [LoanModificationController::class, 'show'])->name('loans.modifications.show');
    Route::post('/loans/{loan}/modifications/complete-early', [LoanModificationController::class, 'completeEarly'])->name('loans.modifications.complete-early');
    Route::post('/loans/{loan}/modifications/restructure', [LoanModificationController::class, 'restructure'])->name('loans.modifications.restructure');
    Route::post('/loans/{loan}/modifications/recovery-plan', [LoanModificationController::class, 'createRecoveryPlan'])->name('loans.modifications.recovery-plan');
    Route::post('/loans/{loan}/modifications/overpayment', [LoanModificationController::class, 'processOverpayment'])->name('loans.modifications.overpayment');
    Route::post('/loans/{loan}/modifications/recalculate', [LoanModificationController::class, 'recalculateSchedule'])->name('loans.modifications.recalculate');
    Route::get('/loans/{loan}/modifications/history', [LoanModificationController::class, 'history'])->name('loans.modifications.history');
    
    Route::view('/reports', 'reports.index')->name('reports.index');
    Route::view('/automation', 'automation.dashboard')->name('automation.dashboard');
    
    // Loan Tracking & Repayment Management
    Route::get('/tracking/active-loans', [LoanTrackingController::class, 'activeLoans'])->name('tracking.active-loans');
    Route::get('/tracking/loans/{loan}', [LoanTrackingController::class, 'trackLoan'])->name('tracking.loan-detail');
    Route::get('/tracking/loans/{loan}/schedule', [LoanTrackingController::class, 'repaymentSchedule'])->name('tracking.schedule');
    Route::get('/tracking/loans/{loan}/record-payment', [LoanTrackingController::class, 'recordPayment'])->name('tracking.record-payment');
    Route::post('/tracking/repayments/{repayment}/payment', [LoanTrackingController::class, 'storePayment'])->name('tracking.store-payment');
    Route::get('/tracking/analytics', [LoanTrackingController::class, 'analytics'])->name('tracking.analytics');
    Route::get('/tracking/overdue', [LoanTrackingController::class, 'overdueLoans'])->name('tracking.overdue');
    Route::post('/tracking/send-reminders', [LoanTrackingController::class, 'sendReminders'])->name('tracking.send-reminders');
    Route::get('/tracking/export-payments', [LoanTrackingController::class, 'exportPaymentReport'])->name('tracking.export-payments');
    Route::get('/tracking/export-loans', [LoanTrackingController::class, 'exportLoanReport'])->name('tracking.export-loans');
    
    // Notification routes
    Route::post('/notifications/send-reminders', [\App\Http\Controllers\NotificationController::class, 'sendReminders'])->name('notifications.send-reminders');
    Route::post('/notifications/test', [\App\Http\Controllers\NotificationController::class, 'testNotification'])->name('notifications.test');
});

// Customer Routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.store');
        Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.store');
    });

    Route::middleware(['auth:customer'])->group(function () {
        Route::get('/home', [CustomerHomeController::class, 'index'])->name('home');
        Route::get('/loan-products', [LoanProductController::class, 'index'])->name('loan-products');
        Route::get('/my-loans', [CustomerLoanController::class, 'index'])->name('loans.index');
        Route::get('/my-loans/apply', [CustomerLoanController::class, 'create'])->name('loans.apply');
        Route::post('/my-loans', [CustomerLoanController::class, 'store'])->name('loans.store');
        Route::get('/my-loans/{loan}', [CustomerLoanController::class, 'show'])->name('loans.show');
        Route::get('/my-loans/{loan}/edit', [CustomerLoanController::class, 'edit'])->name('loans.edit');
        Route::patch('/my-loans/{loan}', [CustomerLoanController::class, 'update'])->name('loans.update');
        Route::get('/notifications', [CustomerNotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{notification}/mark-read', [CustomerNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [CustomerNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::get('/settings', [CustomerSettingsController::class, 'index'])->name('settings');
        Route::get('/profile', [CustomerProfileController::class, 'index'])->name('profile');
        Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
    });

    // Customer Legal Pages
    Route::get('/privacy-policy', function () {
        return view('customer.legal.privacy-policy');
    })->name('privacy-policy');

    Route::get('/terms-of-service', function () {
        return view('customer.legal.terms-of-service');
    })->name('terms-of-service');

    Route::get('/cookie-policy', function () {
        return view('customer.legal.cookie-policy');
    })->name('cookie-policy');
});

// Legal Pages (accessible to all)
Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('terms-of-service');

Route::get('/cookie-policy', function () {
    return view('legal.cookie-policy');
})->name('cookie-policy');

require __DIR__.'/auth.php';
