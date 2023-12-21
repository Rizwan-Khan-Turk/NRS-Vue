<?php

use App\Http\Controllers\SurgicalHistoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FtpController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\NotificationController;

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
Route::withoutMiddleware(['web', 'csrf'])->post('/api/purchaseOrder', [AuditLogController::class, 'store']);
//migrated routes
    //Route::post('/api/purchaseOrder', [AuditLogController::class, 'store']);
    Route::get('/download-ftp-file', [FtpController::class, 'readFileFromFtp'])->name('readFileFromFtp');
    Route::get('/multi-download-ftp-file', [FtpController::class, 'multireadFileFromFtp'])->name('multireadFileFromFtp');
    Route::get('/convert-to-json', [PurchaseOrderController::class, 'convertToJSON']);
    Route::get('/sendInvoice', [PurchaseOrderController::class, 'sendInvoice']);
    Route::get('/sendInvoice-fromFTP', [PurchaseOrderController::class, 'sendInvoicefromFTPServer']);
    Route::get('/posts/store', [PostController::class, 'store']);
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class,'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class,'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/sendEmail',[PurchaseOrderController::class,'sendEmail']);

//end
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
    Route::get('/auditdetailsinvoice/{id}',[AuditLogController::class, 'invoicedetails'])->name('audit.invoiceshow');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/user/fetchAllUsers', [UserController::class, 'fetchAllUsers'])->name('users.fetchAllUsers');
    Route::post('/user/search', [UserController::class, 'search'])->name('user.search');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/{id}', [UserController::class, 'update'])->name('user.update');


    // Dashboard
    Route::get('/dashboard/get', [DashboardController::class, 'getDashboardData'])->name('dashboard.get');




});
