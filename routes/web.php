<?php

use App\Http\Middleware\AdminRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
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

// CART CONTROLLER ROUTES
Route::post('/addToCart', [CartController::class, 'addToCart'])->name('addToCart')->middleware(['auth', 'verified']);
Route::post('/addOne', [CartController::class, 'addOne'])->name('addOne')->middleware(['auth', 'verified']);
Route::post('/deductOne', [CartController::class, 'deductOne'])->name('deductOne')->middleware(['auth', 'verified']);
Route::post('/removeFromCart', [CartController::class, 'removeFromCart'])->name('removeFromCart')->middleware(['auth', 'verified']);


// PRODUCT CONTROLLER ROUTES
Route::get('/productDetails/{id}', [ProductController::class, 'productDetails'])->name('productDetails');
Route::get('/addProduct/{id}', [ProductController::class, 'addProduct'])->name('addProduct');
Route::post('/addProductPost/{id}', [ProductController::class, 'addProductPost'])->name('addProductPost');

// PROFILE CONTROLLER ROUTES
Route::get('/editProfile', [ProfileController::class, 'editProfile'])->name('editProfile')->middleware(['auth', 'verified']);
Route::post('/editProfilePost', [ProfileController::class, 'update'])->name('updateProfile')->middleware(['auth', 'verified']);


// SHOP CONTROLLER ROUTES
Route::post('/setupSeller', [ShopController::class, 'setupSeller'])->name('setupSellerPost')->middleware(['auth', 'verified']);
Route::get('/viewShop/{id}', [ShopController::class, 'viewShop'])->name('viewShop')->middleware(['auth', 'verified']);
Route::get('/viewYourShop/{id}', [ShopController::class, 'viewYourShop'])->name('viewYourShop')->middleware(['auth', 'verified']);


//Auth login register controller
// Route::post('/loginOrRegister', [AuthenticatedSessionController::class, 'loginOrRegister'])->name('loginOrRegister');


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

// Route::get('/', function () {
//     return view('landing');
// })->name('landing');
// ->middleware(['auth', 'verified'])
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
