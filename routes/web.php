<?php

use App\Http\Controllers\SurgicalHistoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AuditLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Vendor
    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor');
    Route::get('/vendor/fetchAllVendors', [VendorController::class, 'fetchAllVendors'])->name('vendor.fetchAll');
    Route::post('/vendor/search', [VendorController::class, 'search'])->name('vendors.search');
    Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendors.destroy');
    Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
    Route::post('/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
    Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
    Route::post('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');

    // Audit Log
    Route::get('/audit', [AuditLogController::class, 'index'])->name('audit');
    Route::get('/audit/fetchAllAuditLog', [AuditLogController::class, 'fetchAllAuditLog'])->name('audit.fetchAllAuditLog');
    Route::post('/audit/search', [AuditLogController::class, 'search'])->name('audit.search');
    Route::get('/auditdetails/{id}',[AuditLogController::class, 'details'])->name('audit.show');
});
