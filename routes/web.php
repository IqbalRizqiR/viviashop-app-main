<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Frontend\HomepageController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

// $cart = Cart::content()->count();
// dd($cart);
// view()->share('countCart', $cart);

// Route::get('/debug-midtrans', [OrderController::class, 'debug']);

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

    Route::get('/instagram', [InstagramController::class, 'getInstagramData'])->name('admin.instagram.index');
    Route::get('/instagram/callback', [InstagramController::class, 'handleCallback'])
        ->name('instagram.callback');
    Route::match(['get','post'], '/instagram/webhook', [InstagramController::class, 'webhook'])
        ->name('instagram.webhook');
        Route::post('orders/complete/{order}', [\App\Http\Controllers\Admin\OrderController::class , 'doComplete'])->name('orders.complete');




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
    Route::get('/barcode/download' , [ProductController::class, 'downloadBarcode'])->name('barcode.download');
    Route::get('/barcode/downloadSingle/{id}' , [ProductController::class, 'downloadSingleBarcode'])->name('barcode.downloadSingle');
    Route::get('/laporan/export', [ReportController::class, 'exportExcel'])->name('laporan.exportExcel');
    // Route::get('/laporan/dataTotal/{awal}/{akhir}', [HomepageController::class, 'getReportsData'])->name('laporan.data');
    Route::get('/laporan/export/{awal}/{akhir}', [HomepageController::class, 'data'])->name('laporan.exportPDF');

    Route::get('/instagram/create', [InstagramController::class, 'create'])->name('instagram.create');
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
    Route::resource('products.product_images', \App\Http\Controllers\Admin\ProductImageController::class);
    Route::get('/products/generateAllBarcodes', [ProductController::class, 'generateBarcodeAll'])->name('products.generateAll');
    Route::get('/products/generateSingleBarcode/{id}', [ProductController::class, 'generateBarcodeSingle'])->name('products.generateSingle');
    Route::resource('slides', \App\Http\Controllers\Admin\SlideController::class);
    Route::get('slides/{slideId}/up', [\App\Http\Controllers\Admin\SlideController::class, 'moveUp']);
    Route::get('slides/{slideId}/down', [\App\Http\Controllers\Admin\SlideController::class, 'moveDown']);

    Route::get('orders/trashed', [\App\Http\Controllers\Admin\OrderController::class , 'trashed'])->name('orders.trashed');
    Route::get('orders/restore/{order:id}', [\App\Http\Controllers\Admin\OrderController::class , 'restore'])->name('orders.restore');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::get('datas', [App\Http\Controllers\Admin\ProductController::class, 'data'])
     ->name('products.data');
    Route::post('ordersAdmin', [\App\Http\Controllers\Admin\OrderController::class , 'storeAdmin'])->name('orders.storeAdmin');
    Route::get('ordersAdmin', [\App\Http\Controllers\Admin\OrderController::class , 'checkPage'])->name('orders.checkPage');
    Route::post('orders/complete/{order}', [\App\Http\Controllers\Admin\OrderController::class , 'doComplete'])->name('orders.complete');
    Route::get('orders/{order:id}/cancel', [\App\Http\Controllers\Admin\OrderController::class , 'cancel'])->name('orders.cancels');
	Route::put('orders/cancel/{order:id}', [\App\Http\Controllers\Admin\OrderController::class , 'doCancel'])->name('orders.cancel');
	Route::put('orders/confirm/{id}', [\App\Http\Controllers\Frontend\OrderController::class , 'confirmPaymentAdmin'])->name('orders.confirmAdmin');

    Route::resource('shipments', \App\Http\Controllers\Admin\ShipmentController::class);

    Route::get('reports/revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/product', [\App\Http\Controllers\Admin\ReportController::class, 'product'])->name('reports.product');
    Route::get('reports/inventory', [\App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/payment', [\App\Http\Controllers\Admin\ReportController::class, 'payment'])->name('reports.payment');
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



Route::get('/download-file/{id}', [OrderController::class, 'downloadFile'])->name('download-file');
    Route::get('orders/confirmPayment/{id}', [\App\Http\Controllers\Frontend\OrderController::class, 'confirmPaymentManual'])->name('orders.confirmation_payment');
    Route::put('orders/confirmPaymentManual/{id}', [\App\Http\Controllers\Frontend\OrderController::class, 'confirmPayment'])->name('orders.confirmPayment');
    Route::get('orders/checkout', [\App\Http\Controllers\Frontend\OrderController::class, 'checkout'])->middleware('auth');
    Route::post('orders/checkout', [\App\Http\Controllers\Frontend\OrderController::class, 'doCheckout'])->name('orders.checkout')->middleware('auth');
    Route::get('orders/cities', [\App\Http\Controllers\Frontend\OrderController::class, 'cities'])->middleware('auth');
    Route::post('orders/shipping-cost', [\App\Http\Controllers\Frontend\OrderController::class, 'shippingCost'])->middleware('auth');
    Route::post('orders/set-shipping', [\App\Http\Controllers\Frontend\OrderController::class, 'setShipping'])->middleware('auth');
    Route::get('orders/received/{orderId}', [\App\Http\Controllers\Frontend\OrderController::class, 'received']);
    Route::get('orders/{orderId}', [\App\Http\Controllers\Frontend\OrderController::class, 'show'])->name('showUsersOrder');
    Route::resource('wishlists', \App\Http\Controllers\Frontend\WishListController::class)->only(['index','store','destroy']);
    Route::resource('orders', \App\Http\Controllers\Frontend\OrderController::class)->only(['index','store','destroy']);

    // Midtrans routes


    Route::get('profile',  [\App\Http\Controllers\Auth\ProfileController::class, 'index'])->name('profile');
    Route::put('profile', [\App\Http\Controllers\Auth\ProfileController::class, 'update']);

});
