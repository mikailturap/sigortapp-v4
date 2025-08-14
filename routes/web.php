<?php

use App\Http\Controllers\PolicyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [PolicyController::class, 'index'])->name('home');
    Route::get('/dashboard', [PolicyController::class, 'dashboard'])->name('dashboard');
    Route::get('/policy-tracking', [PolicyController::class, 'policyTracking'])->name('policies.tracking');
    Route::get('/policy-tracking/export', [PolicyController::class, 'trackingExport'])->name('policies.tracking.export');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Dashboard Privacy API
    Route::get('/api/dashboard-privacy-settings', [App\Http\Controllers\Api\DashboardPrivacyController::class, 'getSettings']);

    // Müşteri Yönetimi - Spesifik route'lar önce gelmeli
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::post('customers/find-or-create', [CustomerController::class, 'findOrCreate'])->name('customers.find-or-create');
    Route::post('customers/check-identity', [CustomerController::class, 'checkByIdentity'])->name('customers.check-identity');
    Route::post('customers/{customer}/payment-schedule', [CustomerController::class, 'addPaymentSchedule'])->name('customers.payment-schedule');
    Route::post('customers/{customer}/payment', [CustomerController::class, 'addPayment'])->name('customers.payment');
    Route::patch('customers/{customer}/payment-schedule/{schedule}/mark-paid', [CustomerController::class, 'markPaymentScheduleAsPaid'])->name('customers.payment-schedule.mark-paid');
    Route::delete('customers/{customer}/payment-schedule/{schedule}', [CustomerController::class, 'deletePaymentSchedule'])->name('customers.payment-schedule.delete');
    Route::resource('customers', CustomerController::class);

    // Policy routes
    Route::resource('policies', PolicyController::class);
    Route::post('/policies/check-customer-identity', [PolicyController::class, 'checkCustomerByIdentity'])->name('policies.check-customer-identity');
    Route::patch('policies/{policy}/toggle-status', [PolicyController::class, 'toggleStatus'])->name('policies.toggleStatus');
    Route::post('policies/bulk-actions', [PolicyController::class, 'bulkActions'])->name('policies.bulkActions');
    Route::get('policies/{policy}/download-pdf', [PolicyController::class, 'downloadPdf'])->name('policies.downloadPdf');
    Route::patch('policies/{policy}/dismiss-notification', [PolicyController::class, 'dismissNotification'])->name('policies.dismissNotification');
    Route::post('policies/{policy}/send-reminder-email', [PolicyController::class, 'sendReminderEmail'])->name('policies.sendReminderEmail');

    // Gelir Yönetimi Route'ları
    Route::prefix('revenue')->name('revenue.')->group(function () {
        Route::get('/', [RevenueController::class, 'index'])->name('index');
        Route::get('/policies', [RevenueController::class, 'policies'])->name('policies');
        Route::get('/policies/{policy}', [RevenueController::class, 'show'])->name('show');
        Route::get('/policies/{policy}/edit', [RevenueController::class, 'edit'])->name('edit');
        Route::put('/policies/{policy}', [RevenueController::class, 'update'])->name('update');
        Route::get('/reports', [RevenueController::class, 'reports'])->name('reports');
        Route::post('/export', [RevenueController::class, 'export'])->name('export');
        
        // Yeni muhasebe sistemi route'ları
        Route::get('/customer-accounts', [RevenueController::class, 'customerAccounts'])->name('customer-accounts');
        Route::get('/payment-transactions', [RevenueController::class, 'paymentTransactions'])->name('payment-transactions');
        Route::get('/cost-analysis', [RevenueController::class, 'costAnalysis'])->name('cost-analysis');
    });

    // Profil sayfası kaldırıldı
});

require __DIR__.'/auth.php';
