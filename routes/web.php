<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProductPriceController;
use App\Http\Controllers\CustomerPurchaseController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\Api\ShopApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth', 'role:superadmin'])->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Penjualan
    Route::resource('sales', SaleController::class);
    
    // Barang
    Route::resource('products', ProductController::class);
    
    // Customer
    Route::resource('customers', CustomerController::class);

    //CustomerProductPrice
    Route::resource('customer-product-price', CustomerProductPriceController::class);
    Route::post('user-customer-product-price', [CustomerProductPriceController::class, 'storeusercustomer'])->name('customer-product-price.storeusercustomer');
    Route::get('/customers/{customerId}/products', [SaleController::class, 'getProductsByCustomer']);
    Route::get('sales/get-price/{customer_id}/{product_id}', [SaleController::class, 'getPrice'])->name('sales.getPrice');
    Route::post('/sales/add-product', [SaleController::class, 'addProduct'])->name('sales.add-product');
    Route::get('/print/{id}', [PrintController::class, 'generatePdf'])->name('print.pdf');

    // Laporan
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/show/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('reports/reportbycustomer/{customer_id}', [ReportController::class, 'reportbycustomer'])->name('reports.reportbycustomer');

    // Route::resource('shipments', ShipmentController::class);
    Route::get('shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('shipments/create/{id}', [ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('shipments', [ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('shipments/{id}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::patch('shipments/{id}/update-status', [ShipmentController::class, 'updateStatus'])->name('shipments.updateStatus');

    Route::group(['prefix' => 'penawaran'], function () {
        Route::get('/', [PenawaranController::class, 'index'])->name('penawaran.index');
        Route::get('/all', [PenawaranController::class, 'allpenawaran'])->name('penawaran.allpenawaran');
        Route::get('/new', [PenawaranController::class, 'create']); 
        Route::post('/', [PenawaranController::class, 'save']);
        Route::get('/detail/{id}', [PenawaranController::class, 'detail'])->name('detail.penawaran');
        Route::post('/save-kondisi', [PenawaranController::class, 'savekondisi']);
        Route::post('/save-harga', [PenawaranController::class, 'saveharga']);
        Route::get('/print/{id}', [PenawaranController::class, 'printpenawaran'])->name('print.penawaran');
        Route::delete('/delete/kondisi/{id}', [PenawaranController::class, 'destroyKondisi']);
        Route::delete('/delete/harga/{id}', [PenawaranController::class, 'destroyHarga']);
        Route::delete('/delete/{id}', [PenawaranController::class, 'destroy']);
        Route::get('/cari/penawaran', [PenawaranController::class, 'cari'])->name('cari.penawaran');
    });

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

});
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Shop Routes
    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [CustomerPurchaseController::class, 'index'])->name('index');
        Route::post('/add-to-cart', [CustomerPurchaseController::class, 'addToCart'])->name('add_to_cart');
        Route::post('/remove-from-cart', [CustomerPurchaseController::class, 'removeFromCart'])->name('remove_from_cart');
        Route::get('/checkout', [CustomerPurchaseController::class, 'checkout'])->name('checkout');
        Route::get('/riwayat', [CustomerPurchaseController::class, 'riwayat'])->name('riwayat');
        Route::get('/detailsinvoice/{id}', [CustomerPurchaseController::class, 'detailsinvoice'])->name('detailsinvoice');
        Route::get('/edit/{id}', [CustomerPurchaseController::class, 'edit'])->name('edit');
        Route::post('/delete', [CustomerPurchaseController::class, 'deletedetails'])->name('shop.delete');
        Route::post('/shop/update-detail', [CustomerPurchaseController::class, 'updateDetail'])->name('shop.update');
        Route::get('edit/editjson/{id}', [ShopApiController::class, 'editjson'])->name('editjson');
        Route::delete('edit/shop/delete-detail/{id}', [ShopApiController::class, 'deleteDetail']);
        Route::post('edit/shop/update/{id}', [ShopApiController::class, 'update'])->name('shop.update');

    });

    // Profile Route
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('profile/update', [AuthController::class, 'update'])->name('profile.update');
    Route::post('profile/updatepassword', [AuthController::class, 'updatepassword'])->name('profile.updatepassword');
    Route::post('profile/updatephoto', [AuthController::class, 'updatePhoto'])->name('profile.updatephoto');

});

// Authentication Routes
Route::prefix('login')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
