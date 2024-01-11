<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ApiAuthController;
use App\Http\Controllers\ApiController;

<<<<<<< HEAD
use App\Http\Controllers\Api\V2\ProfileController;
use App\Http\Controllers\Api\V2\WishlistController;
use App\Http\Controllers\Api\V2\CartController;
use App\Http\Controllers\Api\V2\ProductController;
use App\Http\Controllers\Api\V2\CategoryController;
use App\Http\Controllers\Api\V2\WebsiteController;

=======
>>>>>>> f02c273fcda02281970e443290a75a6fb1ad2d78
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'auth'], function ($router) {
    Route::get('/countries', [ApiController::class, 'getCountries'])->name('countries');
    Route::get('/state/{country_id?}', [ApiController::class, 'getCountryStates'])->name('state');

    Route::post('/forgot-password', [ApiController::class, 'forgotPassword'])->name('forgot-password');
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
    Route::post('/otp-login', [ApiAuthController::class, 'loginWithOTP'])->name('otp-login');
    Route::post('/register', [ApiAuthController::class, 'signup'])->name('register');

    Route::post('/verify-otp', [ApiAuthController::class, 'verifyOTP'])->name('verify-otp');
    Route::post('/resend-otp', [ApiAuthController::class, 'resendOTP'])->name('resend-otp');
   
    Route::get('/user-profile', [ApiAuthController::class, 'userProfile'])->name('user-profile'); 
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout', [ApiAuthController::class, 'logout']);
        Route::get('user-profile', [ApiAuthController::class, 'user']);
        Route::post('update-profile', [ApiAuthController::class, 'updateProfile'])->name('update-profile');
        Route::post('change-password', [ApiAuthController::class, 'changePassword'])->name('change-password');
        Route::post('add-address', [ApiAuthController::class, 'addAddress'])->name('add-address');
        Route::post('update-address', [ApiAuthController::class, 'updateAddress'])->name('update-address');
        Route::post('set-default-address', [ApiAuthController::class, 'setDefaultAddress'])->name('set-default-address');
        Route::post('delete-address', [ApiAuthController::class, 'deleteAddress'])->name('delete-address');
    });

<<<<<<< HEAD
Route::group(['middleware' => 'api'], function ($router) {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::group(['prefix' => 'profile'], function () {
            Route::get('account', [ProfileController::class, 'getUserAccountInfo']);
        });

        Route::apiResource('wishlists', WishlistController::class)->only('index', 'store', 'destroy');
        Route::get('wishlists/count', [WishlistController::class, 'getCount']);
        Route::post('wishlist/remove', [WishlistController::class, 'removeWishlistItem']);

        Route::get('cart/count', [CartController::class, 'getCount']);
        Route::post('cart/change_quantity', [CartController::class, 'changeQuantity']);
        Route::post('cart/remove', [CartController::class, 'removeCartItem']);
        Route::apiResource('cart', CartController::class)->only('index', 'store', 'destroy');
    });

    Route::get('products', [ProductController::class, 'index']);
    // Route::get('categories', [CategoryController::class, 'index']);

    Route::group(['prefix' => 'website'], function () {
        Route::get('categories', [WebsiteController::class, 'websiteCategories']);
        Route::get('brands', [WebsiteController::class, 'websiteBrands']);
    });
=======
>>>>>>> f02c273fcda02281970e443290a75a6fb1ad2d78
});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
