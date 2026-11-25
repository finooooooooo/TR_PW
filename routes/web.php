<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('rooms', RoomController::class)->except(['show']);
    Route::resource('tenants', TenantController::class)->except(['show']);
    Route::resource('leases', LeaseController::class)->except(['show']);
    Route::resource('payments', PaymentController::class)->except(['show']);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/tenants/pdf', [ReportController::class, 'tenantsPdf'])->name('reports.tenants.pdf');
    Route::get('/reports/payments/pdf', [ReportController::class, 'paymentsPdf'])->name('reports.payments.pdf');
});