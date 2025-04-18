<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryExportController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Dashboards\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\Order\DueOrderController;
use App\Http\Controllers\Order\OrderCompleteController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderPendingController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductExportController;
use App\Http\Controllers\Product\ProductImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Quotation\QuotationController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierExportController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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


Route::middleware('guest')->group(function () {
    // Route cho sản phẩm
    Route::get('/client/products', [ClientController::class, 'productsIndex'])->name('product_client.index');
    Route::get('/client', [ClientController::class, 'productsIndex'])->name('product_client.index');
    Route::get('/client/products/{uuid}', [ClientController::class, 'productsShow'])->name('product_client.show');

    // Route cho nhà cung cấp
    Route::get('/client/suppliers', [ClientController::class, 'suppliersIndex'])->name('supplier_client.index');
    Route::get('/client/suppliers/{uuid}', [ClientController::class, 'suppliersShow'])->name('supplier_client.show');
    Route::get('/client/suppliers/{uuid}/products', [ClientController::class, 'supplierProductsIndex'])->name('supplier_client.products.index');
    Route::get('/client/category/{slug}/products', [ClientController::class, 'categoryProductsIndex'])->name('category_client.products.show');
    Route::get('/client/category', [ClientController::class, 'categoryIndex'])->name('category_client.index');
    Route::get('clients/{uuid}/payments', [ClientController::class, 'view_bill'])->name('clients.payments.index');

});

// Route gốc
Route::get('/', function () {
    // Kiểm tra nếu người dùng đã đăng nhập
    if (Auth::check()) {
        // Nếu đã đăng nhập, điều hướng đến trang dashboard
        return redirect('/dashboard');
    }
    // Nếu chưa đăng nhập, hiển thị trang loading
    return view('loading');
});

// Route kiểm tra
Route::get('test/', function () {
    return view('test');
})->withoutMiddleware('auth');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('php/', function () {
        return phpinfo();
    });

    // User Management
    // Route::resource('/users', UserController::class); //->except(['show']);
    Route::put('/user/change-password/{username}', [UserController::class, 'updatePassword'])->name('users.updatePassword');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::get('/profile/store-settings', [ProfileController::class, 'store_settings'])->name('profile.store.settings');
    Route::post('/profile/store-settings', [ProfileController::class, 'store_settings_store'])->name('profile.store.settings.store');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/quotations', QuotationController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::get('/export-supplier', [SupplierExportController::class, 'create'])->name('export_supplier');

// Route để hiển thị sản phẩm của nhà cung cấp
    Route::get('suppliers/{uuid}/products', [ProductController::class, 'indexBySupplier'])->name('suppliers.products');

    Route::resource('/categories', CategoryController::class);
    Route::resource('/units', UnitController::class);

    // Route Products
    Route::get('products/import/', [ProductImportController::class, 'create'])->name('products.import.view');
    Route::post('products/import/', [ProductImportController::class, 'store'])->name('products.import.store');
    Route::get('products/export/', [ProductExportController::class, 'create'])->name('products.export.store');
    Route::resource('/products', ProductController::class);
    // Định nghĩa route cho toggle visibility
    Route::get('/products/toggle-visibility/{uuid}', [ProductController::class, 'toggleVisibility'])->name('products.toggleVisibility');

    // Route POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/cart/add', [PosController::class, 'addCartItem'])->name('pos.addCartItem');
    Route::post('/pos/cart/update/{rowId}', [PosController::class, 'updateCartItem'])->name('pos.updateCartItem');
    Route::delete('/pos/cart/delete/{rowId}', [PosController::class, 'deleteCartItem'])->name('pos.deleteCartItem');

    //Route::post('/pos/invoice', [PosController::class, 'createInvoice'])->name('pos.createInvoice');
    Route::post('invoice/create/', [InvoiceController::class, 'create'])->name('invoice.create');

    // Route Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/pending', OrderPendingController::class)->name('orders.pending');
    Route::get('/orders/complete', OrderCompleteController::class)->name('orders.complete');
    Route::get('/customers/{uuid}/orders', [OrderController::class, 'detail'])->name('customers.orders');

    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');

    // SHOW ORDER
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/update/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/cancel/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');

    // DUES
    Route::get('due/orders/', [DueOrderController::class, 'index'])->name('due.index');
    Route::get('due/order/view/{order}', [DueOrderController::class, 'show'])->name('due.show');
    Route::get('due/order/edit/{order}', [DueOrderController::class, 'edit'])->name('due.edit');
    Route::put('due/order/update/{order}', [DueOrderController::class, 'update'])->name('due.update');

    // TODO: Remove from OrderController
    Route::get('/orders/details/{order_id}/download', [OrderController::class, 'downloadInvoice'])->name('order.downloadInvoice');


    // Route Purchases
    Route::get('/purchases/approved', [PurchaseController::class, 'approvedPurchases'])->name('purchases.approvedPurchases');
    Route::get('/purchases/report', [PurchaseController::class, 'purchaseReport'])->name('purchases.purchaseReport');
    Route::get('/purchases/report/export', [PurchaseController::class, 'getPurchaseReport'])->name('purchases.getPurchaseReport');
    Route::post('/purchases/report/export', [PurchaseController::class, 'exportPurchaseReport'])->name('purchases.exportPurchaseReport');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

    //Route::get('/purchases/show/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

    //Route::get('/purchases/edit/{purchase}', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::get('/purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::post('/purchases/update/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('/purchases/delete/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.delete');
    // routes/api.php
    Route::get('categories/{category}/export', [CategoryExportController::class, 'exportByCategory'])->name('categories.export');
    Route::get('/export-invoices', [CategoryExportController::class, 'exportInvoices'])->name('export.invoices');

// Route để hiển thị thông tin thanh toán cho nhà cung cấp
    Route::get('/suppliers/{uuid}/payments', [SupplierController::class, 'view_bill'])
        ->name('suppliers.payments.index');

// Route để xử lý lưu thông tin thanh toán
    Route::post('/suppliers/{uuid}/payments', [SupplierController::class, 'store_bill'])
        ->name('suppliers.payments.store');

    Route::get('/api/953ce2c72476e8a57ab482f9c079a2ab/logAll', [LogController::class, 'getLogs']);
    Route::get('/api/953ce2c72476e8a57ab482f9c079a2ab/sessions', [SessionController::class, 'getAllSessions']);
    Route::get('/ilog', [LogController::class, 'showILog']);

    // Route hiển thị danh sách các IP
    Route::get('/logs', [LogController::class, 'listIPs'])->name('logs.ips');

    // Route hiển thị danh sách thiết bị theo IP
    Route::get('/logs/{ip}', [LogController::class, 'listDevices'])->name('logs.devices');

    // Route hiển thị log của thiết bị theo IP và device
    Route::get('/logs/{ip}/{device}', [LogController::class, 'showLog'])->name('logs.show');
    // Route Quotations
    // Route::get('/quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
//    Route::post('/quotations/complete/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
//    Route::delete('/quotations/delete/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.delete');
});

Route::post(
    "/upload-file",
    [App\Http\Controllers\FileUploadController::class, "uploadFileToCloud"]
);

Route::get('/test', function () {
    try {
        // Thử liệt kê các file
        $files = Storage::disk('gcs')->allFiles();

        // Thử tạo file test
        Storage::disk('gcs')->put('test.txt', 'Hello Cloud Storage');

        return [
            'success' => true,
            'files' => $files,
            'config' => [
                'project_id' => config('filesystems.disks.gcs.project_id'),
                'bucket' => config('filesystems.disks.gcs.bucket'),
                'default_disk' => config('filesystems.default'),
            ]
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'config' => [
                'project_id' => config('filesystems.disks.gcs.project_id'),
                'bucket' => config('filesystems.disks.gcs.bucket'),
                'default_disk' => config('filesystems.default'),
            ]
        ];
    }
});

Route::get('/debug-gcs', function() {
    $keyPath = env('GOOGLE_CLOUD_KEY_FILE_PATH');
    $keyContent = file_get_contents($keyPath);

    return [
        'file_exists' => file_exists($keyPath),
        'is_readable' => is_readable($keyPath),
        'json_valid' => json_decode($keyContent, true) ? true : false,
        'project_id_in_json' => json_decode($keyContent, true)['project_id'] ?? null,
    ];
});

Route::get('/test-gcs', function() {
    try {
        // Upload file (không set visibility)
        Storage::disk('gcs')->put('test.txt', 'Test content');

        // Kiểm tra URL (nếu bucket được cấu hình public)
        $url = Storage::disk('gcs')->url('test.txt');
        return "File uploaded! URL: " . $url;
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// routes/web.php
Route::get('/products-swipe', function () {
    return view('client.products_swipe');
})->name('product_client.swipe');


require __DIR__ . '/auth.php';

