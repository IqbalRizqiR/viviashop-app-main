<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;



use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StockCardController;
use App\Http\Controllers\Admin\SmartPrintConverterController;
use App\Http\Controllers\Frontend\HomepageController;


// Auth guest routes App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;


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

// TEMPORARY: Clear all caches - remove after production is fixed
Route::get('/clear-cache-x7k9', function () {
    \Artisan::call('route:clear');
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('view:clear');
    return 'All caches cleared! Routes: ' . \Artisan::output();
});

Auth::routes();

Route::get('storage/{path}', function($path) {
    $full = storage_path('app/public/' . $path);
    if (!file_exists($full)) {
        abort(404);
    }
    return response()->file($full);
})->where('path', '.*');



Route::post('payments/notification', [App\Http\Controllers\Frontend\OrderController::class, 'notificationHandler'])
    ->name('payment.notification')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    Route::get('payments/client-key', [App\Http\Controllers\Frontend\OrderController::class, 'getMidtransClientKey'])
    ->name('payment.client-key');

    Route::get('payments/finish', [App\Http\Controllers\Frontend\OrderController::class, 'finishRedirect'])
    ->name('payment.finish');

    Route::get('payments/unfinish', [App\Http\Controllers\Frontend\OrderController::class, 'unfinishRedirect'])
    ->name('payment.unfinish');

    Route::get('payments/error', [App\Http\Controllers\Frontend\OrderController::class, 'errorRedirect'])
    ->name('payment.error');

	// Customer invoice and order status routes
	Route::get('orders/invoice/{id}', [App\Http\Controllers\Frontend\OrderController::class, 'invoice'])
		->name('orders.invoice')
		->middleware('auth');
		
	Route::get('orders/status/{id}', [App\Http\Controllers\Frontend\OrderController::class, 'getOrderStatus'])
		->name('orders.status');

	Route::get('orders/complete/{order}', [App\Http\Controllers\Frontend\OrderController::class, 'complete'])
		->name('orders.complete.page')
		->middleware('auth');

	Route::post('orders/complete/{order}', [App\Http\Controllers\Frontend\OrderController::class, 'doComplete'])
		->name('orders.complete')
		->middleware('auth');

    Route::get('/instagram', [InstagramController::class, 'getInstagramData'])->name('admin.instagram.index');
    Route::get('/instagram/callback', [InstagramController::class, 'handleCallback'])
        ->name('instagram.callback');
    Route::match(['get','post'], '/instagram/webhook', [InstagramController::class, 'webhook'])
        ->name('instagram.webhook');




Route::group(['middleware' => ['auth', 'is_admin'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    // admin
    Route::post('/products/find-barcode', [ProductController::class, 'findByBarcode'])
     ->name('products.find-barcode');
    Route::get('/orders/invoices/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'invoices'])
     ->name('orders.invoices');
     Route::get('/products/exportTemplate', [ProductController::class, 'exportTemplate'])->name("products.exportTemplate");
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('users/edit/{id}', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('users/delete/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::resource('setting', SettingController::class);
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/updateHargaJual/{id}', [PembelianController::class, 'updateHargaJual'])->name('updateHargaJual');
    Route::put('/updateHargaBeli/{id}', [PembelianController::class, 'updateHargaBeli'])->name('updateHargaBeli');
    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::resource('/pembelian', PembelianController::class)->except('create');
    Route::get('/pembeliansss/data', [PembelianController::class, 'data'])->name('pembelian.data');
    Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::resource('/pembelian_detail', PembelianDetailController::class)->except('create', 'show', 'edit');
    Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
    Route::get('/pembelian/invoices/{id}', [PembelianController::class, 'invoices'])
        ->name('pembelian.invoices');
    Route::get('reports/revenue/{awal}/{akhir}/excel',
        [App\Http\Controllers\Frontend\HomepageController::class, 'exportExcel']
    )->name('reports.revenue.excel');
    Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
    Route::get('/pembelian_detail/variants/{productId}', [PembelianDetailController::class, 'getVariants'])->name('pembelian_detail.get_variants');
    Route::get('/pembelian_detail/realtime-stock/{pembelianId}', [PembelianDetailController::class, 'getRealtimeStock'])->name('pembelian_detail.realtime_stock');
    Route::get('/pembelian_detail/editBayar/{id}', [PembelianDetailController::class, 'editBayar'])->name('pembelian_detail.editBayar');
    Route::put('/pembelian_detail/updateEdit/{id}', [PembelianDetailController::class, 'updateEdit'])->name('pembelian_detail.updateEdit');

    Route::resource('supplier', SupplierController::class);
    Route::get('/laporan', [HomepageController::class, 'reports'])->name('laporan');
    Route::get('/laporan/data/{awal}/{akhir}', [HomepageController::class, 'data'])->name('laporan.data');
    Route::post('/products/import', [ProductController::class, 'imports'])->name('products.imports');
    Route::get('/quaggaTest', function () {
        return view('admin.products.quaggaTest');
    })->name('quaggaTest');
    Route::get('/downloadExcel', function () {
        return response()->download(public_path('template.xlsx'));
        // dd(public_path('/file'));
    })->name('downloadTemplate');
    
    Route::get('/barcode/preview' , [ProductController::class, 'previewBarcode'])->name('barcode.preview');
    Route::get('/barcode/preview/landscape' , [ProductController::class, 'previewBarcodeLandscape'])->name('barcode.preview.landscape');
    Route::get('/barcode/preview/portrait' , [ProductController::class, 'previewBarcodePortrait'])->name('barcode.preview.portrait');
    Route::get('/barcode/print/landscape' , [ProductController::class, 'printBarcodeLandscape'])->name('barcode.print.landscape');
    Route::get('/barcode/print/portrait' , [ProductController::class, 'printBarcodePortrait'])->name('barcode.print.portrait');
    Route::get('/barcode/downloadSingle/{id}' , [ProductController::class, 'downloadSingleBarcode'])->name('barcode.downloadSingle');
    Route::get('/laporan/export', [ReportController::class, 'exportExcel'])->name('laporan.exportExcel');
    // Route::get('/laporan/dataTotal/{awal}/{akhir}', [HomepageController::class, 'getReportsData'])->name('laporan.data');
    Route::get('/laporan/export/{awal}/{akhir}', [HomepageController::class, 'data'])->name('laporan.exportPDF');

    Route::get('/instagram/create', [InstagramController::class, 'create'])->name('instagram.create');
    
    // Smart Print Converter Routes
    Route::get('/smart-print-converter', [SmartPrintConverterController::class, 'index'])
        ->name('smart-print-converter.index');
    Route::post('/smart-print-converter/convert/{id}', [SmartPrintConverterController::class, 'convert'])
        ->name('smart-print-converter.convert');
    Route::post('/smart-print-converter/bulk-convert', [SmartPrintConverterController::class, 'bulkConvert'])
        ->name('smart-print-converter.bulk-convert');
        
    // Smart Print Variant Manager Routes
    Route::get('/smart-print-variant', [\App\Http\Controllers\Admin\SmartPrintVariantController::class, 'index'])
        ->name('smart-print-variant.index');
    Route::post('/smart-print-variant/auto-fix', [\App\Http\Controllers\Admin\SmartPrintVariantController::class, 'autoFix'])
        ->name('smart-print-variant.auto-fix');
    Route::post('/smart-print-variant/create-variants/{id}', [\App\Http\Controllers\Admin\SmartPrintVariantController::class, 'createVariants'])
        ->name('smart-print-variant.create-variants');
    Route::post('/instagram/post', [InstagramController::class, 'postToInstagram'])->name('instagram.store');
    Route::get('/instagram/postProduct/{id}', [InstagramController::class, 'postToInstagramFromProducts'])
        ->name('instagram.postProduct');
    Route::get('/instagram/data', [InstagramController::class, 'getInstagramData'])->name('instagram.data');
    Route::get('/instagram/redirect', [InstagramController::class, 'redirectToInstagram'])
     ->name('instagram.redirect');
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('attributes', \App\Http\Controllers\Admin\AttributeController::class);
    Route::resource('attributes.attribute_variants', \App\Http\Controllers\Admin\AttributeVariantController::class);
    Route::resource('attributes.attribute_variants.attribute_options', \App\Http\Controllers\Admin\AttributeOptionController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::get('/products/data/datatable', [ProductController::class, 'data'])->name('products.data');
    Route::get('/products/{id}/attributes', [ProductController::class, 'getProductAttributes'])->name('products.attributes');
    Route::get('/products/{id}/variant-options', [ProductController::class, 'getVariantOptions'])->name('products.variant-options');
    Route::get('/products/{id}/all-variants', [ProductController::class, 'getAllVariants'])->name('products.all-variants');
    Route::post('/products/barcode/search', [ProductController::class, 'findByBarcode'])->name('products.findByBarcode');
    Route::delete('/products/{id}/delete-variants', [ProductController::class, 'deleteVariants'])->name('products.deleteVariants');
    Route::post('/variants/create', [\App\Http\Controllers\Admin\ProductVariantController::class, 'store'])->name('variants.create');
    Route::get('/variants/{id}', [\App\Http\Controllers\Admin\ProductVariantController::class, 'show'])->name('variants.show');
    Route::put('/variants/{id}', [\App\Http\Controllers\Admin\ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('/variants/{id}', [\App\Http\Controllers\Admin\ProductVariantController::class, 'destroy'])->name('variants.destroy');
    Route::resource('products.product_images', \App\Http\Controllers\Admin\ProductImageController::class);
    Route::get('/products/generateAllBarcodes', [ProductController::class, 'generateBarcodeAll'])->name('products.generateAll');
    Route::post('/products/generateAllBarcodes', [ProductController::class, 'generateBarcodeAll'])->name('products.generateAll');
    Route::get('/products/generateSingleBarcode/{id}', [ProductController::class, 'generateBarcodeSingle'])->name('products.generateSingle');
    Route::resource('slides', \App\Http\Controllers\Admin\SlideController::class);
    Route::get('slides/{slideId}/up', [\App\Http\Controllers\Admin\SlideController::class, 'moveUp']);
    Route::get('slides/{slideId}/down', [\App\Http\Controllers\Admin\SlideController::class, 'moveDown']);

    Route::get('orders/trashed', [\App\Http\Controllers\Admin\OrderController::class , 'trashed'])->name('orders.trashed');
    Route::get('orders/restore/{order:id}', [\App\Http\Controllers\Admin\OrderController::class , 'restore'])->name('orders.restore');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::get('datas', [App\Http\Controllers\Admin\ProductController::class, 'data'])
     ->name('products.admin.data');
    Route::post('ordersAdmin', [\App\Http\Controllers\Admin\OrderController::class , 'storeAdmin'])->name('orders.storeAdmin');
    Route::get('ordersAdmin', [\App\Http\Controllers\Admin\OrderController::class , 'checkPage'])->name('orders.checkPage');
    Route::post('orders/payment-notification', [\App\Http\Controllers\Admin\OrderController::class , 'paymentNotification'])->name('orders.payment-notification');
    Route::post('orders/{order}/generate-payment-token', [\App\Http\Controllers\Admin\OrderController::class , 'generatePaymentToken'])->name('orders.generate-payment-token');
    Route::get('orders/complete/{order}', [\App\Http\Controllers\Admin\OrderController::class , 'complete'])->name('orders.complete.page');
    Route::post('orders/complete/{order}', [\App\Http\Controllers\Admin\OrderController::class , 'doComplete'])->name('orders.complete');
    Route::post('orders/confirm-pickup/{order}', [\App\Http\Controllers\Admin\OrderController::class , 'confirmPickup'])->name('orders.confirmPickup');
    Route::post('orders/{order}/employee-tracking', [\App\Http\Controllers\Admin\OrderController::class, 'updateEmployeeTracking'])->name('orders.updateEmployeeTracking');
    Route::post('orders/{order}/toggle-tracking', [\App\Http\Controllers\Admin\OrderController::class, 'toggleEmployeeTracking'])->name('orders.toggleEmployeeTracking');
    Route::post('orders/adjust-shipping', [\App\Http\Controllers\Admin\OrderController::class, 'adjustShipping'])->name('orders.adjustShipping');
    
    // Admin payment callback routes
    Route::get('orders/payment/finish', [\App\Http\Controllers\Admin\OrderController::class, 'paymentFinishRedirect'])->name('payment.finish');
    Route::get('orders/payment/unfinish', [\App\Http\Controllers\Admin\OrderController::class, 'paymentUnfinishRedirect'])->name('payment.unfinish');
    Route::get('orders/payment/error', [\App\Http\Controllers\Admin\OrderController::class, 'paymentErrorRedirect'])->name('payment.error');
    Route::get('orders/{order:id}/cancel', [\App\Http\Controllers\Admin\OrderController::class , 'cancel'])->name('orders.cancels');
	Route::put('orders/cancel/{order:id}', [\App\Http\Controllers\Admin\OrderController::class , 'doCancel'])->name('orders.cancel');
	Route::put('orders/confirm/{id}', [\App\Http\Controllers\Frontend\OrderController::class , 'confirmPaymentAdmin'])->name('orders.confirmAdmin');

    Route::resource('shipments', \App\Http\Controllers\Admin\ShipmentController::class);

    Route::get('reports/revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/product', [\App\Http\Controllers\Admin\ReportController::class, 'product'])->name('reports.product');
    Route::get('reports/inventory', [\App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/payment', [\App\Http\Controllers\Admin\ReportController::class, 'payment'])->name('reports.payment');
    
    Route::get('employee-performance', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'index'])->name('employee-performance.index');
    Route::get('employee-performance/data', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'data'])->name('employee-performance.data');
    Route::get('employee-performance/bonus', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusForm'])->name('employee-performance.bonus');
    Route::get('employee-performance/bonus/list', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusList'])->name('employee-performance.bonusList');
    Route::get('employee-performance/bonus/data', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusData'])->name('employee-performance.bonusData');
    Route::get('employee-performance/bonus/{id}/detail', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusDetail'])->name('employee-performance.bonusDetail');
    Route::get('employee-performance/bonus/{id}/edit', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusEdit'])->name('employee-performance.bonusEdit');
    Route::put('employee-performance/bonus/{id}', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusUpdate'])->name('employee-performance.bonusUpdate');
    Route::delete('employee-performance/bonus/{id}', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusDelete'])->name('employee-performance.bonusDelete');
    Route::get('employee-performance/{employee}', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'show'])->name('employee-performance.show');
    Route::post('employee-performance/bonus', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'giveBonus'])->name('employee-performance.giveBonus');
    Route::get('employee-performance-bonus-history', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusHistory'])->name('employee-performance.bonusHistory');
    
    Route::prefix('print-service')->name('print-service.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PrintServiceController::class, 'index'])->name('index');
        Route::get('/queue', [\App\Http\Controllers\Admin\PrintServiceController::class, 'queue'])->name('queue');
        Route::get('/sessions', [\App\Http\Controllers\Admin\PrintServiceController::class, 'sessions'])->name('sessions');
        Route::get('/orders', [\App\Http\Controllers\Admin\PrintServiceController::class, 'orders'])->name('orders');
        Route::get('/reports', [\App\Http\Controllers\Admin\PrintServiceController::class, 'reports'])->name('reports');
        Route::get('/stock', [\App\Http\Controllers\Admin\PrintServiceController::class, 'stockManagement'])->name('stock');
        Route::get('/stock-report', [\App\Http\Controllers\Admin\PrintServiceController::class, 'stockReport'])->name('stock-report');
        Route::post('/generate-session', [\App\Http\Controllers\Admin\PrintServiceController::class, 'generateSession'])->name('generate-session');
        Route::post('/orders/{id}/confirm-payment', [\App\Http\Controllers\Admin\PrintServiceController::class, 'confirmPayment'])->name('confirm-payment');
        Route::post('/orders/{id}/print', [\App\Http\Controllers\Admin\PrintServiceController::class, 'printOrder'])->name('print-order');
        Route::post('/orders/{id}/print-files', [\App\Http\Controllers\Admin\PrintServiceController::class, 'printFiles'])->name('print-files');
        Route::post('/orders/{id}/complete', [\App\Http\Controllers\Admin\PrintServiceController::class, 'completeOrder'])->name('complete-order');
        Route::post('/orders/{id}/cancel', [\App\Http\Controllers\Admin\PrintServiceController::class, 'cancelOrder'])->name('cancel-order');
        Route::post('/stock/{variantId}/adjust', [\App\Http\Controllers\Admin\PrintServiceController::class, 'adjustStock'])->name('stock.adjust');
        Route::get('/orders/{id}/payment-proof', [\App\Http\Controllers\Admin\PrintServiceController::class, 'downloadPaymentProof'])->name('payment-proof');
        Route::get('/view-file/{fileId}', [\App\Http\Controllers\Admin\PrintServiceController::class, 'viewFile'])->name('view-file');
    });

    Route::prefix('stock')->as('stock.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\StockCardController::class, 'index'])->name('index');
        Route::get('/movements', [\App\Http\Controllers\Admin\StockCardController::class, 'movements'])->name('movements');
        Route::get('/movements/data', [\App\Http\Controllers\Admin\StockCardController::class, 'movementData'])->name('movements.data');
        Route::get('/card/{variantId}', [\App\Http\Controllers\Admin\StockCardController::class, 'show'])->name('show');
        Route::get('/product/{productId}', [\App\Http\Controllers\Admin\StockCardController::class, 'showProduct'])->name('product');
        Route::get('/report', [\App\Http\Controllers\Admin\StockCardController::class, 'report'])->name('report');
    });

    Route::resource('paper-types', \App\Http\Controllers\Admin\PaperTypeController::class);
    Route::get('/paper-types/api/active', [\App\Http\Controllers\Admin\PaperTypeController::class, 'getActivePaperTypes'])->name('paper-types.api.active');
    
    Route::resource('print-types', \App\Http\Controllers\Admin\PrintTypeController::class);
    Route::get('/print-types/api/active', [\App\Http\Controllers\Admin\PrintTypeController::class, 'getActivePrintTypes'])->name('print-types.api.active');
});

Route::get('/smart-print', function () {
    $setting = App\Models\Setting::first();
    view()->share('setting', $setting);
    
    $cart = Gloudemans\Shoppingcart\Facades\Cart::content()->count();
    view()->share('countCart', $cart);
    
    return view('frontend.smart-print.index');
})->name('frontend.print-service');

Route::prefix('print-service')->group(function () {
    Route::post('/upload', [\App\Http\Controllers\PrintServiceController::class, 'upload'])->name('print-service.upload');
    Route::delete('/file/{file_id}', [\App\Http\Controllers\PrintServiceController::class, 'deleteFile'])->name('print-service.delete-file');
    Route::get('/preview/{file_id}', [\App\Http\Controllers\PrintServiceController::class, 'previewFile'])->name('print-service.preview-file');
    Route::get('/products', [\App\Http\Controllers\PrintServiceController::class, 'getProducts'])->name('print-service.products');
    Route::post('/get-session-files', [\App\Http\Controllers\PrintServiceController::class, 'getSessionFiles'])->name('print-service.get-session-files');
    Route::post('/calculate', [\App\Http\Controllers\PrintServiceController::class, 'calculate'])->name('print-service.calculate');
    Route::post('/checkout', [\App\Http\Controllers\PrintServiceController::class, 'checkout'])->name('print-service.checkout');
    Route::get('/status/{orderCode}', [\App\Http\Controllers\PrintServiceController::class, 'status'])->name('print-service.status');
    Route::post('/print/{orderCode}', [\App\Http\Controllers\PrintServiceController::class, 'print'])->name('print-service.print');
    Route::post('/complete/{orderCode}', [\App\Http\Controllers\PrintServiceController::class, 'complete'])->name('print-service.complete');
    Route::post('/generate-session', [\App\Http\Controllers\PrintServiceController::class, 'generateSession'])->name('print-service.generate-session');
    Route::post('/midtrans-callback', [\App\Http\Controllers\PrintServiceController::class, 'midtransCallback'])->name('print-service.midtrans-callback');
    
    // Payment callback routes
    Route::get('/payment/finish', [\App\Http\Controllers\PrintServiceController::class, 'paymentFinish'])->name('print-service.payment.finish');
    Route::get('/payment/unfinish', [\App\Http\Controllers\PrintServiceController::class, 'paymentUnfinish'])->name('print-service.payment.unfinish');
    Route::get('/payment/error', [\App\Http\Controllers\PrintServiceController::class, 'paymentError'])->name('print-service.payment.error');
    
    // Route::get('/test-view-file/{fileId}', [\App\Http\Controllers\Admin\PrintServiceController::class, 'viewFile'])->name('print-service.test-view-file');
    Route::get('/{token}', [\App\Http\Controllers\PrintServiceController::class, 'index'])->name('print-service.customer');
});

Route::get('/', [\App\Http\Controllers\Frontend\HomepageController::class, 'index'])->name('index');
Route::get('products', [\App\Http\Controllers\Frontend\ProductController::class, 'index']);
Route::get('product/{product:slug}', [\App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('product.detail');
Route::get('products/quick-view/{product:slug}', [\App\Http\Controllers\Frontend\ProductController::class, 'quickView']);
Route::get('/shop', [HomepageController::class, 'shop'])->name('shop');
Route::get('/shopCetak', [HomepageController::class, 'shopCetak'])->name('shopCetak');
Route::get('/shopCategory/{slug}', [HomepageController::class, 'shopCategory'])->name('shopCategory');
Route::get('/shop/detail/{id}', [HomepageController::class, 'detail'])->name('shop-detail');

Route::group(['middleware' => 'auth'], function() {
    Route::get('carts', [\App\Http\Controllers\Frontend\CartController::class, 'index'])->name('carts.index');
    Route::post('carts', [\App\Http\Controllers\Frontend\CartController::class, 'store'])->name('carts.store');
    Route::post('carts/update', [\App\Http\Controllers\Frontend\CartController::class, 'update']);
    Route::get('carts/remove/{cartId}', [\App\Http\Controllers\Frontend\CartController::class, 'destroy']);



Route::get('/download-file/{id}', [\App\Http\Controllers\Frontend\OrderController::class, 'downloadFile'])->name('download-file');
    Route::get('orders/confirmPayment/{id}', [\App\Http\Controllers\Frontend\OrderController::class, 'confirmPaymentManual'])->name('orders.confirmation_payment');
    Route::put('orders/confirmPaymentManual/{id}', [\App\Http\Controllers\Frontend\OrderController::class, 'confirmPayment'])->name('orders.confirmPayment');
    Route::get('orders/checkout', [\App\Http\Controllers\Frontend\OrderController::class, 'checkout'])->middleware('auth');
    Route::post('orders/checkout', [\App\Http\Controllers\Frontend\OrderController::class, 'doCheckout'])->name('orders.checkout')->middleware('auth');
    Route::post('orders/shipping-cost', [\App\Http\Controllers\Frontend\OrderController::class, 'shippingCost'])->name('orders.shippingCost')->middleware('auth');
    Route::post('orders/set-shipping', [\App\Http\Controllers\Frontend\OrderController::class, 'setShipping'])->middleware('auth');
    Route::get('orders/received/{orderId}', [\App\Http\Controllers\Frontend\OrderController::class, 'received']);
    Route::get('orders/{orderId}', [\App\Http\Controllers\Frontend\OrderController::class, 'show'])->name('showUsersOrder');
    Route::resource('wishlists', \App\Http\Controllers\Frontend\WishListController::class)->only(['index','store','destroy']);
    
    Route::resource('orders', \App\Http\Controllers\Frontend\OrderController::class)->only(['index','store','destroy']);

    // Midtrans routes


    Route::get('profile',  [\App\Http\Controllers\Auth\ProfileController::class, 'index'])->name('profile');
    Route::put('profile', [\App\Http\Controllers\Auth\ProfileController::class, 'update']);

});

// Location endpoints (no auth required for dropdown data)
Route::get('api/provinces', [\App\Http\Controllers\Frontend\OrderController::class, 'provinces']);
Route::get('api/cities/{province_id}', [\App\Http\Controllers\Frontend\OrderController::class, 'cities']);
Route::get('api/districts/{city_id}', [\App\Http\Controllers\Frontend\OrderController::class, 'districts']);
Route::get('/api/attribute-options/{attributeId}/{variantId}', function($attributeId, $variantId) {
    $options = \App\Models\AttributeOption::where('attribute_variant_id', $variantId)->get();
    return response()->json(['options' => $options]);
})->name('api.attribute-options');

 