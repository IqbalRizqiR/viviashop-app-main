<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductVariantController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Product Variant API Routes
Route::prefix('products')->name('api.products.')->group(function () {
    Route::get('{product}/variants', [ProductVariantController::class, 'getVariantsByProduct'])
        ->name('variants');
    
    Route::get('{product}/variants/options', [ProductVariantController::class, 'getVariantOptions'])
        ->name('variant-options');
    
    Route::get('{product}/attributes/{attributeName}/options', [ProductVariantController::class, 'getAttributeOptions'])
        ->name('attribute-options');
    
    Route::post('{product}/variant-by-attributes', [ProductVariantController::class, 'getVariantByAttributes'])
        ->name('variant-by-attributes');
});

Route::prefix('variants')->name('api.variants.')->group(function () {
    Route::post('/', [ProductVariantController::class, 'store'])
        ->name('store')
        ->middleware('auth:sanctum');
    
    Route::get('{variant}/stock', [ProductVariantController::class, 'checkStock'])
        ->name('check-stock');
    
    Route::get('low-stock', [ProductVariantController::class, 'getLowStockVariants'])
        ->name('low-stock');
    
    Route::put('{variant}/stock', [ProductVariantController::class, 'updateStock'])
        ->name('update-stock')
        ->middleware('auth:sanctum');
    
    Route::put('{product}/bulk-stock', [ProductVariantController::class, 'bulkUpdateStock'])
        ->name('bulk-update-stock')
        ->middleware('auth:sanctum');
});
