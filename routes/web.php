<?php

use App\Http\Controllers\v1\AppController;
use App\Http\Controllers\v1\CustomerController;
use App\Http\Controllers\v1\OrderController;
use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\ReturnController;
use App\Http\Controllers\v1\SupplierController;
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

Route::controller(AppController::class)->group(function () {
  Route::view("/auth/reset-password", "auth.reset", ['title' => 'Reset Password']);
  Route::post("/auth/reset-password", "resetPassword");
  Route::view("/auth/new-password", "auth.password", ["title" => "New Password"]);
  Route::post("/auth/new-password", "newPassword");
  Route::view("/auth/login", "auth.login", ['title' => 'Login'])->name("login");
  Route::view("/auth/verify-secret-code", "auth.verify", ["title" => "Verify Secret Code"]);
  Route::post("/auth/verify-secret-code", "checkSecretCode");
  Route::post("/auth/login", "handleLogin");
  Route::get('/test', 'test');
});
Route::middleware(["auth", "admin"])->group(function () {
  Route::controller(AppController::class)->group(function () {
    Route::redirect("/", "/dashboard");
    Route::get("/dashboard", "index")->name("dashboard");
    Route::get("/settings", "settings")->name("settings");
    Route::post("/update-profile", "updateProfile");
    Route::get("/stocks", "stocks")->name("stocks");
    Route::get("/sales-report", "salesReport")->name("sales.report");
    Route::get("/search", "globalSearch")->name("search");
    Route::post("/auth/logout", "handleLogout")->name("auth.logout");
    Route::get('/invoices/order', 'invoiceOrders')->name('invoice.orders');
    Route::get('/invoices/supplier', 'invoiceSuppliers')->name('invoice.supliers');

    Route::get('/invoices/customer/{token}', 'getInvoiceOrders')->name('invoice.orders.get');
    Route::get('/invoices/supplier/{token}', 'getInvoiceSuppliers')->name('invoice.supliers.get');
  });
  // products routes
  Route::controller(ProductController::class)->group(function () {
    Route::get("/products", "index")->name("products"); //show all products in a page
    Route::get('/product/add', function () {
      return view('pages.add_product', ['title' => 'Add Product']);
    })->name('product.new');
    Route::get("/products/{query}", "view")->name("products.query"); //show all products by query
    Route::get("/product/edit/{id}", "edit")->name("product.edit"); //form for editing a product
    Route::get("/product/show/{id}", "show")->name("product.show"); // page for a particular product
    Route::get("/product/price", "price"); //get the price of a product
    Route::put("/product/store", "store")->name("product.store"); //store the changes made to a product
    Route::post("/product/add", "create")->name("product.add"); //create a new product
    Route::put("/product/update", "update")->name("product.update"); //update quantity of a product
    Route::post("/product/delete/{id}", "destroy")->name("product.delete"); //delete the product
    Route::get('/product/open-stock', 'openStock')->name('open-stock');
    // Route::post('/product/open-stock', 'openStock')->name('open-stock');
  });
  // suppliers routes
  Route::controller(SupplierController::class)->group(function () {
    Route::get("/suppliers", "index")->name("suppliers"); //show all suppliers in a page
    Route::get("/supplier/data/{supplier}", "getSupplier")->name("supplier.data");
    Route::get("/supplier/edit/{id}", "edit")->name("supplier.edit"); //form for editing a supplier
    Route::get("/supplier/show/{id}", "show")->name("supplier.show"); // page for a particular supplier
    Route::post("/supplier/saved/order/filter", "filter_order")->name("supplier.filter.order"); // page for a particular supplier
    Route::get("/supplier/create-invoice", "showCreateInvoice")->name('supplier.showCreateInvoice');
    Route::post("/supplier/create-invoice","createInvoice")->name("supplier.invoice.add");
    Route::post("/supplier/store/{id}", "store")->name("supplier.store"); //store the changes made to a supplier
    Route::post("/supplier/add", "create")->name("supplier.add"); //create a new supplier
    Route::put("/supplier/update", "update")->name("supplier.update"); //update quantity of a supplier
    Route::post("/supplier/delete/{id}", "destroy")->name("supplier.delete"); //delete the supplier
    Route::get("/order/supplier/saved/{id}", "show_saved_order")->name("order.supplier.show"); // page for a particular order
    Route::post("/order/supplier/saved/{id}", "update_order")->name("order.supplier.save"); // page for a particular order
    Route::post("/order/supplier/saved/delete/{id}", "deleteOrder"); // page for a particular order
  });
  // customers routes
  Route::controller(CustomerController::class)->group(function () {
    Route::get("/customers", "index")->name("customers"); //show all suppliers in a page
    Route::get("/customer/edit/{id}", "edit")->name("customer.edit"); //form for editing a customer
    Route::get("/customer/stock/{id}", "show")->name("customer.show"); // page for a particular customer
    Route::post("/customer/saved/order/filter", "filter_and_update")->name('order.filter');
    Route::post("/customer/store/{id}", "store")->name("customer.store"); //store the changes made to a customer
    Route::post("/customer/add", "create")->name("customer.add"); //create a new customer
    Route::put("/customer/update", "update")->name("customer.update"); //update quantity of a customer
    Route::post("/customer/delete/{id}", "destroy")->name("customer.delete"); //delete the customer
  });
  // orders routes
  Route::controller(OrderController::class)->group(function () {
    Route::get("/customer/orders", "index")->name("orders.customer"); //show all suppliers in a page
    Route::get("/supplier/orders", "supplierOrders")->name("orders.supplier");
    Route::get("/product/find", "search");
    Route::get("/orders/all", "getOrders")->name("orders.all"); //show all suppliers in a page
    Route::get("/order/edit/{id}", "edit")->name("order.edit"); //form for editing a order
    Route::get("/order/show/{id}", "show")->name("order.show"); // page for a particular order
    Route::get('/order/saved/{id}', 'show_and_update')->name('order.show.update');
    Route::post('/order/saved/{id}', 'update_and_save')->name('order.show.save');
    Route::post("/order/store/{id}", "store")->name("order.store"); //store the changes made to a order
    Route::post("/order/add", "create")->name("order.add"); //create a new order
    Route::put("/order/update", "update")->name("order.update"); //update quantity of a order
    Route::put('/order/saved/today/delete', 'deleteAll')->name('order.today.delete');
    Route::post("/order/delete/{id}", "destroy")->name('order.delete'); //delete the order
    Route::delete('/delete/saved-order', 'deleteMultiple')->name('delete.order.multiple');
  });
  Route::controller(ReturnController::class)->group(function () {
    Route::get('/returns', 'index')->name('returns');
    Route::get('/return/edit/{id}', 'edit')->name('return.edit');
    Route::post('/return/add', 'create')->name('return.add');
    Route::post('/return/reset/{ID}/{order_id}', 'ResetReturn');
  });
});
