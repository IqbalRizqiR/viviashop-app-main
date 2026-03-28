<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Legacy API Routes (Backward Compatibility)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Api\ProductVariantController;

Route::prefix('products')->name('api.products.')->group(function () {
    Route::get('{product}/variants', [ProductVariantController::class, 'getVariantsByProduct'])->name('variants');
    Route::get('{product}/variants/options', [ProductVariantController::class, 'getVariantOptions'])->name('variant-options');
    Route::get('{product}/attributes/{attributeName}/options', [ProductVariantController::class, 'getAttributeOptions'])->name('attribute-options');
    Route::post('{product}/variant-by-attributes', [ProductVariantController::class, 'getVariantByAttributes'])->name('variant-by-attributes');
});

Route::prefix('variants')->name('api.variants.')->group(function () {
    Route::post('/', [ProductVariantController::class, 'store'])->name('store')->middleware('auth:sanctum');
    Route::get('{variant}/stock', [ProductVariantController::class, 'checkStock'])->name('check-stock');
    Route::get('low-stock', [ProductVariantController::class, 'getLowStockVariants'])->name('low-stock');
    Route::put('{variant}/stock', [ProductVariantController::class, 'updateStock'])->name('update-stock')->middleware('auth:sanctum');
    Route::put('{product}/bulk-stock', [ProductVariantController::class, 'bulkUpdateStock'])->name('bulk-update-stock')->middleware('auth:sanctum');
});

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ShippingController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\WishlistController;
use App\Http\Controllers\Api\V1\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\V1\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\V1\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\V1\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Api\V1\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\V1\Admin\EmployeePerformanceController as AdminEmployeeController;
use App\Http\Controllers\Api\V1\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Api\V1\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\V1\Admin\StockController as AdminStockController;
use App\Http\Controllers\Api\V1\Admin\PrintServiceController as AdminPrintServiceController;
use App\Http\Controllers\Api\V1\Admin\StockCardController as AdminStockCardController;
use App\Http\Controllers\Api\V1\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Api\V1\PrintServiceController;

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ═══════════════════════════════════════════════════════════
    //  PUBLIC ROUTES
    // ═══════════════════════════════════════════════════════════

    // ─── Auth ──────────────────────────────────────────────────
    Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');

    // ─── Products ──────────────────────────────────────────────
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/popular', [ProductController::class, 'popular'])->name('products.popular');
    Route::get('products/{slug}', [ProductController::class, 'show'])->name('products.show');
    Route::get('products/category/{slug}', [ProductController::class, 'byCategory'])->name('products.by-category');
    Route::get('categories', [ProductController::class, 'categories'])->name('categories.index');
    Route::get('brands', [ProductController::class, 'brands'])->name('brands.index');

    // ─── Shipping ──────────────────────────────────────────────
    Route::get('shipping/provinces', [ShippingController::class, 'provinces'])->name('shipping.provinces');
    Route::get('shipping/cities/{provinceId}', [ShippingController::class, 'cities'])->name('shipping.cities');
    Route::get('shipping/districts/{cityId}', [ShippingController::class, 'districts'])->name('shipping.districts');
    Route::post('shipping/cost', [ShippingController::class, 'cost'])->name('shipping.cost');

    // ─── Payments (Webhook — no auth) ──────────────────────────
    Route::post('payments/notification', [PaymentController::class, 'notification'])->name('payments.notification');
    Route::get('payments/client-key', [PaymentController::class, 'clientKey'])->name('payments.client-key');

    // ─── Settings (Public) ─────────────────────────────────────
    Route::get('settings', fn() => response()->json(['success' => true, 'data' => \App\Models\Setting::first()]))->name('settings.index');
    Route::get('slides', fn() => response()->json(['success' => true, 'data' => \App\Models\Slide::active()->orderBy('position')->get()]))->name('slides.index');

    // ─── Print Service (Public) ─────────────────────────────
    Route::get('print-service/products', [PrintServiceController::class, 'products'])->name('print-service.products');
    Route::get('print-service/order/{orderCode}', [PrintServiceController::class, 'orderStatus'])->name('print-service.order-status');

    // ═══════════════════════════════════════════════════════════
    //  AUTHENTICATED ROUTES
    // ═══════════════════════════════════════════════════════════

    Route::middleware('auth:sanctum')->group(function () {

        // ─── Auth ──────────────────────────────────────────────
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me');
        Route::put('auth/profile', [AuthController::class, 'updateProfile'])->name('auth.profile');

        // ─── Cart ──────────────────────────────────────────────
        Route::get('cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('cart', [CartController::class, 'store'])->name('cart.store');
        Route::put('cart/{itemId}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('cart/{itemId}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::delete('cart', [CartController::class, 'clear'])->name('cart.clear');

        // ─── Orders ────────────────────────────────────────────
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('orders/{id}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
        Route::get('orders/{id}/received', [OrderController::class, 'received'])->name('orders.received');
        Route::post('orders/{id}/complete', [OrderController::class, 'complete'])->name('orders.complete');

        // ─── Payments ──────────────────────────────────────────
        Route::post('payments/token', [PaymentController::class, 'generateToken'])->name('payments.token');

        // ─── Wishlists ─────────────────────────────────────────
        Route::get('wishlists', [WishlistController::class, 'index'])->name('wishlists.index');
        Route::post('wishlists', [WishlistController::class, 'store'])->name('wishlists.store');
        Route::delete('wishlists/{id}', [WishlistController::class, 'destroy'])->name('wishlists.destroy');

        // ─── Print Service (Auth'd) ────────────────────────────
        Route::post('print-service/session', [PrintServiceController::class, 'createSession'])->name('print-service.session');
        Route::post('print-service/order', [PrintServiceController::class, 'submitOrder'])->name('print-service.submit');
        Route::post('print-service/order/{orderCode}/payment-proof', [PrintServiceController::class, 'uploadPaymentProof'])->name('print-service.payment-proof');

        // ═══════════════════════════════════════════════════════
        //  ADMIN ROUTES
        // ═══════════════════════════════════════════════════════

        Route::prefix('admin')->name('admin.')->middleware('api_admin')->group(function () {

            // Dashboard
            Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // Orders
            Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/trashed', [AdminOrderController::class, 'trashed'])->name('orders.trashed');
            Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
            Route::put('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
            Route::post('orders/{id}/confirm', [AdminOrderController::class, 'confirm'])->name('orders.confirm');
            Route::post('orders/{id}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
            Route::post('orders/{id}/complete', [AdminOrderController::class, 'complete'])->name('orders.complete');
            Route::post('orders/{id}/assign-employee', [AdminOrderController::class, 'assignEmployee'])->name('orders.assign-employee');
            Route::put('orders/{id}/shipping', [AdminOrderController::class, 'updateShipping'])->name('orders.shipping');
            Route::get('orders/{id}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
            Route::delete('orders/{id}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
            Route::post('orders/{id}/restore', [AdminOrderController::class, 'restore'])->name('orders.restore');

            // Products
            Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
            Route::post('products', [AdminProductController::class, 'store'])->name('products.store');
            Route::get('products/{id}', [AdminProductController::class, 'show'])->name('products.show');
            Route::put('products/{id}', [AdminProductController::class, 'update'])->name('products.update');
            Route::delete('products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
            Route::post('products/barcode', [AdminProductController::class, 'findByBarcode'])->name('products.barcode');
            Route::post('products/{id}/generate-barcode', [AdminProductController::class, 'generateBarcode'])->name('products.generate-barcode');

            // Categories
            Route::apiResource('categories', AdminCategoryController::class);

            // Brands
            Route::apiResource('brands', AdminBrandController::class)->except(['show']);

            // Users
            Route::apiResource('users', AdminUserController::class);

            // Employee Performance
            Route::get('employees', [AdminEmployeeController::class, 'index'])->name('employees.index');
            Route::get('employees/list', [AdminEmployeeController::class, 'employeeList'])->name('employees.list');
            Route::get('employees/{name}', [AdminEmployeeController::class, 'details'])->name('employees.details');
            Route::post('employees/bonus', [AdminEmployeeController::class, 'updateBonus'])->name('employees.bonus');

            // Reports
            Route::get('reports/revenue', [AdminReportController::class, 'revenue'])->name('reports.revenue');
            Route::get('reports/products', [AdminReportController::class, 'productPerformance'])->name('reports.products');

            // Stock
            Route::get('stock', [AdminStockController::class, 'index'])->name('stock.index');
            Route::get('stock/variants', [AdminStockController::class, 'variantStock'])->name('stock.variants');
            Route::get('stock/dead', [AdminStockController::class, 'deadStock'])->name('stock.dead');
            Route::get('stock/card/{productId}', [AdminStockController::class, 'stockCard'])->name('stock.card');
            Route::put('stock/{productId}', [AdminStockController::class, 'updateStock'])->name('stock.update');
            Route::put('stock/variant/{variantId}', [AdminStockController::class, 'updateVariantStock'])->name('stock.update-variant');

            // Settings
            Route::get('settings', [AdminSettingController::class, 'settings'])->name('settings.index');
            Route::put('settings', [AdminSettingController::class, 'updateSettings'])->name('settings.update');

            // Slides
            Route::get('slides', [AdminSettingController::class, 'slides'])->name('slides.index');
            Route::post('slides', [AdminSettingController::class, 'storeSlide'])->name('slides.store');
            Route::put('slides/{id}', [AdminSettingController::class, 'updateSlide'])->name('slides.update');
            Route::delete('slides/{id}', [AdminSettingController::class, 'destroySlide'])->name('slides.destroy');

            // Suppliers
            Route::get('suppliers', [AdminSettingController::class, 'suppliers'])->name('suppliers.index');
            Route::post('suppliers', [AdminSettingController::class, 'storeSupplier'])->name('suppliers.store');
            Route::put('suppliers/{id}', [AdminSettingController::class, 'updateSupplier'])->name('suppliers.update');
            Route::delete('suppliers/{id}', [AdminSettingController::class, 'destroySupplier'])->name('suppliers.destroy');

            // Purchases
            Route::get('purchases', [AdminSettingController::class, 'purchases'])->name('purchases.index');
            Route::post('purchases', [AdminSettingController::class, 'storePurchase'])->name('purchases.store');
            Route::delete('purchases/{id}', [AdminSettingController::class, 'destroyPurchase'])->name('purchases.destroy');

            // Print Service
            Route::get('print-service/dashboard', [AdminPrintServiceController::class, 'dashboard'])->name('print-service.dashboard');
            Route::get('print-service/queue', [AdminPrintServiceController::class, 'queue'])->name('print-service.queue');
            Route::get('print-service/sessions', [AdminPrintServiceController::class, 'sessions'])->name('print-service.sessions');
            Route::get('print-service/orders', [AdminPrintServiceController::class, 'orders'])->name('print-service.orders');
            Route::post('print-service/generate-session', [AdminPrintServiceController::class, 'generateSession'])->name('print-service.generate-session');
            Route::post('print-service/{id}/confirm-payment', [AdminPrintServiceController::class, 'confirmPayment'])->name('print-service.confirm-payment');
            Route::post('print-service/{id}/print', [AdminPrintServiceController::class, 'printOrder'])->name('print-service.print');
            Route::get('print-service/{id}/files', [AdminPrintServiceController::class, 'printFiles'])->name('print-service.files');
            Route::post('print-service/{id}/complete', [AdminPrintServiceController::class, 'completeOrder'])->name('print-service.complete');
            Route::post('print-service/{id}/cancel', [AdminPrintServiceController::class, 'cancelOrder'])->name('print-service.cancel');
            Route::get('print-service/reports', [AdminPrintServiceController::class, 'reports'])->name('print-service.reports');
            Route::get('print-service/stock', [AdminPrintServiceController::class, 'stockManagement'])->name('print-service.stock');
            Route::post('print-service/stock/{variantId}/adjust', [AdminPrintServiceController::class, 'adjustStock'])->name('print-service.adjust-stock');
            Route::get('print-service/{id}/payment-proof', [AdminPrintServiceController::class, 'downloadPaymentProof'])->name('print-service.payment-proof');

            // Stock Card
            Route::get('stock-card', [AdminStockCardController::class, 'index'])->name('stock-card.index');
            Route::get('stock-card/variant/{variantId}', [AdminStockCardController::class, 'show'])->name('stock-card.show');
            Route::get('stock-card/movements', [AdminStockCardController::class, 'movements'])->name('stock-card.movements');
            Route::get('stock-card/report', [AdminStockCardController::class, 'report'])->name('stock-card.report');
            Route::get('stock-card/product/{productId}', [AdminStockCardController::class, 'showProduct'])->name('stock-card.product');

            // Product Images
            Route::get('products/{productId}/images', [AdminProductImageController::class, 'index'])->name('product-images.index');
            Route::post('products/{productId}/images', [AdminProductImageController::class, 'store'])->name('product-images.store');
            Route::delete('products/{productId}/images/{imageId}', [AdminProductImageController::class, 'destroy'])->name('product-images.destroy');
            Route::put('products/{productId}/images/reorder', [AdminProductImageController::class, 'reorder'])->name('product-images.reorder');
        });
    });
});
