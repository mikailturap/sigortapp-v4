<?php

use App\Http\Controllers\PolicyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PolicyTypeController;
use App\Http\Controllers\InsuranceCompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [PolicyController::class, 'index'])->name('home');
    Route::get('/dashboard', [PolicyController::class, 'dashboard'])->name('dashboard');
    Route::get('/policy-tracking', [PolicyController::class, 'policyTracking'])->name('policies.tracking');
    Route::get('/policy-tracking/export', [PolicyController::class, 'trackingExport'])->name('policies.tracking.export');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Poliçe Türleri Yönetimi
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('policy-types', PolicyTypeController::class);
        Route::patch('policy-types/{policyType}/deactivate', [PolicyTypeController::class, 'deactivate'])->name('policy-types.deactivate');
        Route::patch('policy-types/{policyType}/activate', [PolicyTypeController::class, 'activate'])->name('policy-types.activate');
        Route::post('policy-types/update-order', [PolicyTypeController::class, 'updateOrder'])->name('policy-types.update-order');
        Route::get('policy-types-api/active', [PolicyTypeController::class, 'getActive'])->name('policy-types.active');

        Route::resource('insurance-companies', InsuranceCompanyController::class);
        Route::patch('insurance-companies/{insuranceCompany}/deactivate', [InsuranceCompanyController::class, 'deactivate'])->name('insurance-companies.deactivate');
        Route::patch('insurance-companies/{insuranceCompany}/activate', [InsuranceCompanyController::class, 'activate'])->name('insurance-companies.activate');
        Route::post('insurance-companies/update-order', [InsuranceCompanyController::class, 'updateOrder'])->name('insurance-companies.update-order');
        Route::get('insurance-companies-api/active', [InsuranceCompanyController::class, 'getActive'])->name('insurance-companies.active');
    });

    // Dashboard Gizlilik API'si
    Route::get('/api/dashboard-privacy-settings', [App\Http\Controllers\Api\DashboardPrivacyController::class, 'getSettings']);

    // Müşteri Yönetimi - Spesifik route'lar önce gelmeli
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::post('customers/find-or-create', [CustomerController::class, 'findOrCreate'])->name('customers.find-or-create');
    Route::post('customers/check-identity', [CustomerController::class, 'checkByIdentity'])->name('customers.check-identity');
    Route::post('customers/{customer}/payment-schedule', [CustomerController::class, 'addPaymentSchedule'])->name('customers.payment-schedule');
    Route::post('customers/{customer}/payment', [CustomerController::class, 'addPayment'])->name('customers.payment');
    Route::patch('customers/{customer}/payment-schedule/{schedule}/mark-paid', [CustomerController::class, 'markPaymentScheduleAsPaid'])->name('customers.payment-schedule.mark-paid');
    Route::delete('customers/{customer}/payment-schedule/{schedule}', [CustomerController::class, 'deletePaymentSchedule'])->name('customers.payment-schedule.delete');
    Route::resource('customers', CustomerController::class);

    // Poliçe dosya route'ları, uygun eşleşme için kısıtlama ile
    Route::get('policies/{policy}/files/{file}/download', [PolicyController::class, 'downloadFile'])
        ->where(['policy' => '[0-9]+', 'file' => '[0-9]+'])
        ->name('policies.files.download');
    Route::get('policies/{policy}/files/{file}/preview', [PolicyController::class, 'previewFile'])
        ->where(['policy' => '[0-9]+', 'file' => '[0-9]+'])
        ->name('policies.files.preview');
    Route::delete('policies/{policy}/files/{file}', [PolicyController::class, 'deleteFile'])
        ->where(['policy' => '[0-9]+', 'file' => '[0-9]+'])
        ->name('policies.files.delete');
    
    // Poliçe kaynak route'ları (çakışmaları önlemek için destroy hariç)
    Route::resource('policies', PolicyController::class)->except(['destroy']);
    // Poliçe destroy route'u dosya route'larından sonra gelir
    Route::delete('policies/{policy}', [PolicyController::class, 'destroy'])->name('policies.destroy');
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
