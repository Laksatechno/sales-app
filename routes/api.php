<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShopApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



// Route::middleware(['auth:web', 'role:customer'])->group(function () {
//     Route::get('shopa', [ShopApiController::class, 'index']);
//     Route::get('shop/editjson/{id}', [ShopApiController::class, 'editjson'])->name('editjson');
//     Route::post('shop/update/{id}', [ShopApiController::class, 'update']);
//     Route::delete('shop/delete-detail', [ShopApiController::class, 'deleteDetail']);
// });
Route::post('edit/shop/update', [ShopApiController::class, 'update'])->name('shop.update');
