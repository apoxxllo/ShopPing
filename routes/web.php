<?php

use App\Http\Middleware\AdminRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

//HOME CONTROLLER ROUTES
Route::get('/', [HomeController::class, 'landing'])->name('landing');
Route::get('/shops', [HomeController::class, 'shops'])->name('shops');
Route::get('/yourShops', [HomeController::class, 'yourShops'])->name('yourShops')->middleware(['auth', 'verified']);
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart')->middleware(['auth', 'verified']);
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout')->middleware(['auth', 'verified']);
Route::get('/setupSeller', [HomeController::class, 'setupSeller'])->name('setupSeller')->middleware(['auth', 'verified']);
Route::get('/viewCategory/{id}', [HomeController::class, 'viewCategory'])->name('viewCategory')->middleware(['auth', 'verified']);
Route::get('/orderHistory', [HomeController::class, 'orderHistory'])->name('orderHistory')->middleware(['auth', 'verified']);
Route::get('/notifications', [HomeController::class, 'notifications'])->name('notifications')->middleware(['auth', 'verified']);
Route::get('/favorites', [HomeController::class, 'favorites'])->name('favorites')->middleware(['auth', 'verified']);
Route::get('/favoriteProduct/{id}', [HomeController::class, 'favoriteProduct'])->name('favoriteProduct')->middleware(['auth', 'verified']);
Route::get('/favoriteShop/{id}', [HomeController::class, 'favoriteShop'])->name('favoriteShop')->middleware(['auth', 'verified']);


// CART CONTROLLER ROUTES
Route::post('/addToCart', [CartController::class, 'addToCart'])->name('addToCart')->middleware(['auth', 'verified']);
Route::post('/addOne', [CartController::class, 'addOne'])->name('addOne')->middleware(['auth', 'verified']);
Route::post('/deductOne', [CartController::class, 'deductOne'])->name('deductOne')->middleware(['auth', 'verified']);
Route::post('/removeFromCart', [CartController::class, 'removeFromCart'])->name('removeFromCart')->middleware(['auth', 'verified']);


// PRODUCT CONTROLLER ROUTES
Route::get('/productDetails/{id}', [ProductController::class, 'productDetails'])->name('productDetails');
Route::get('/addProduct/{id}', [ProductController::class, 'addProduct'])->name('addProduct')->middleware(['auth', 'verified']);
Route::get('/search-products', [ProductController::class, 'search'])->name('products.search');


Route::post('/addProductPost/{id}', [ProductController::class, 'addProductPost'])->name('addProductPost')->middleware(['auth', 'verified']);
Route::post('/updateProduct', [ProductController::class, 'updateProduct'])->name('updateProduct')->middleware(['auth', 'verified']);
Route::post('/addProduct', [ProductController::class, 'addProductFromManage'])->name('addProductFromManage')->middleware(['auth', 'verified']);
Route::post('/deleteProductPost', [ProductController::class, 'deleteProduct'])->name('deleteProductPost')->middleware(['auth', 'verified']);
Route::post('/reviewProduct/{id}', [ProductController::class, 'reviewProduct'])->name('reviewProduct')->middleware(['auth', 'verified']);

// PROFILE CONTROLLER ROUTES
Route::get('/editProfile', [ProfileController::class, 'editProfile'])->name('editProfile')->middleware(['auth', 'verified']);

Route::post('/editProfilePost', [ProfileController::class, 'update'])->name('updateProfile')->middleware(['auth', 'verified']);


// SHOP CONTROLLER ROUTES
Route::post('/setupSeller', [ShopController::class, 'setupSeller'])->name('setupSellerPost')->middleware(['auth', 'verified']);
Route::post('/reviewShop/{id}', [ShopController::class, 'reviewShop'])->name('reviewShop')->middleware(['auth', 'verified']);
Route::post('/editShop', [ShopController::class, 'editShopPost'])->name('editShopPost')->middleware(['auth', 'verified']);


Route::get('/viewShop/{id}', [ShopController::class, 'viewShop'])->name('viewShop')->middleware(['auth', 'verified']);
Route::get('/viewYourShop/{id}', [ShopController::class, 'viewYourShop'])->name('viewYourShop')->middleware(['auth', 'verified']);
Route::get('/manageProducts/{id}', [ShopController::class, 'manageProducts'])->name('manageProducts')->middleware(['auth', 'verified']);
Route::get('/editShop/{id}', [ShopController::class, 'editShop'])->name('editShop')->middleware(['auth', 'verified']);

// ORDER CONTROLLER ROUTES
Route::post('/placeOrder', [OrderController::class, 'placeOrder'])->name('placeOrder')->middleware(['auth', 'verified']);
Route::post('/sendOrder/{id}', [OrderController::class, 'sendOrder'])->name('sendOrder')->middleware(['auth', 'verified']);
Route::post('/receiveOrder/{id}', [OrderController::class, 'receiveOrder'])->name('receiveOrder')->middleware(['auth', 'verified']);

Route::get('/manageOrders/{id}', [OrderController::class, 'manageOrders'])->name('manageOrders')->middleware(['auth', 'verified']);


Route::get('/pingSeller/{id}', [OrderController::class, 'pingSeller'])->name('pingSeller')->middleware(['auth', 'verified']);


// ADMIN CONTROLLER ROUTES
Route::get('/admin', [AdminController::class, 'index']) ->name('adminDashboard')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::get('/productsAdmin', [AdminController::class, 'products']) ->name('productsAdmin')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::get('/categoriesAdmin', [AdminController::class, 'categories']) ->name('categoriesAdmin')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::get('/usersAdmin', [AdminController::class, 'users']) ->name('usersAdmin')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::get('/shopsAdmin', [AdminController::class, 'shops']) ->name('shopsAdmin')
    ->middleware(['auth', 'verified', AdminRole::class]);

Route::post('/addCategory', [AdminController::class, 'addCategory']) ->name('addCategory')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/addProductAdmin', [AdminController::class, 'addProductAdmin']) ->name('addProductAdmin')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/deleteCategory', [AdminController::class, 'deleteCategory']) ->name('deleteCategory')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/deleteProduct', [AdminController::class, 'deleteProduct']) ->name('deleteProduct')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/updateProductAdmin', [AdminController::class, 'updateProductAdmin']) ->name('updateProductAdmin')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/updateCategory', [AdminController::class, 'updateCategory']) ->name('updateCategory')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/addUser', [AdminController::class, 'addUser']) ->name('addUser')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/deleteUser', [AdminController::class, 'deleteUser']) ->name('deleteUser')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/updateUser', [AdminController::class, 'updateUser']) ->name('updateUser')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/updateShop', [AdminController::class, 'updateShop']) ->name('updateShop')
    ->middleware(['auth', 'verified', AdminRole::class]);
Route::post('/deleteShop', [AdminController::class, 'deleteShop']) ->name('deleteShop')
    ->middleware(['auth', 'verified', AdminRole::class]);

// Route::get('/', function () {
//     return view('landing');
// })->name('landing');
// ->middleware(['auth', 'verified'])
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
