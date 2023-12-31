<?php

/*
|--------------------------------------------------------------------------
| Auction Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    
    // Auction product lists
    Route::get('auction/all-products', [AuctionProductController::class,'all_auction_product_list'])->name('auction.all_products');
    Route::get('auction/inhouse-products', [AuctionProductController::class,'inhouse_auction_products'])->name('auction.inhouse_products');
    Route::get('auction/seller-products', [AuctionProductController::class,'seller_auction_products'])->name('auction.seller_products');

    Route::get('/auction-product/create', [AuctionProductController::class,'product_create_admin'])->name('auction_product_create.admin');
    Route::post('/auction-product/store', [AuctionProductController::class,'product_store_admin'])->name('auction_product_store.admin');
    Route::get('/auction_products/edit/{id}', [AuctionProductController::class,'product_edit_admin'])->name('auction_product_edit.admin');
    Route::post('/auction_products/update/{id}', [AuctionProductController::class,'product_update_admin'])->name('auction_product_update.admin');
    Route::get('/auction_products/destroy/{id}', [AuctionProductController::class,'product_destroy_admin'])->name('auction_product_destroy.admin');

    Route::get('/product-bids/{id}', [AuctionProductBidController::class,'product_bids_admin'])->name('product_bids.admin');
    Route::get('/product-bids/destroy/{id}', [AuctionProductBidController::class,'bid_destroy_admin'])->name('product_bids_destroy.admin');


    // Sales
    Route::get('/auction_products-orders', [AuctionProductController::class,'admin_auction_product_orders'])->name('auction_products_orders');
});

Route::group(['prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user']], function() {
    Route::get('/auction_products', [AuctionProductController::class,'auction_product_list_seller'])->name('auction_products.seller.index');

    Route::get('/auction-product/create', [AuctionProductController::class,'product_create_seller'])->name('auction_product_create.seller');
    Route::post('/auction-product/store', [AuctionProductController::class,'product_store_seller'])->name('auction_product_store.seller');
    Route::get('/auction_products/edit/{id}', [AuctionProductController::class,'product_edit_seller'])->name('auction_product_edit.seller');
    Route::post('/auction_products/update/{id}', [AuctionProductController::class,'product_update_seller'])->name('auction_product_update.seller');
    Route::get('/auction_products/destroy/{id}', [AuctionProductController::class,'product_destroy_seller'])->name('auction_product_destroy.seller');

    Route::get('/product-bids/{id}', [AuctionProductBidController::class,'product_bids_seller'])->name('product_bids.seller');
    Route::get('/product-bids/destroy/{id}', [AuctionProductBidController::class,'bid_destroy_seller'])->name('product_bids_destroy.seller');

    Route::get('/auction_products-orders', [AuctionProductController::class,'seller_auction_product_orders'])->name('auction_products_orders.seller');
});

Route::group(['middleware' => ['auth']], function() {

    Route::resource('auction_product_bids', 'AuctionProductBidController');
    Route::post('/auction/cart/show-cart-modal', [CartController::class,'showCartModalAuction'])->name('auction.cart.showCartModal');
    Route::get('/auction/purchase_history', [AuctionProductController::class,'purchase_history_user'])->name('auction_product.purchase_history');
});

Route::post('/home/section/auction_products', [HomeController::class,'load_auction_products_section'])->name('home.section.auction_products');
Route::get('/auction-product/{slug}', [AuctionProductController::class,'auction_product_details'])->name('auction-product');
Route::get('/auction-products', [AuctionProductController::class,'all_auction_products'])->name('auction_products.all');
