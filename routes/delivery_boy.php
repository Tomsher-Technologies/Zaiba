<?php

/*
|--------------------------------------------------------------------------
| POS Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    //Delivery Boy
    Route::resource('delivery-boys', 'DeliveryBoyController');
    Route::get('/delivery-boy/ban/{id}', [DeliveryBoyController::class,'ban'])->name('delivery-boy.ban');
    Route::get('/delivery-boy-configuration', [DeliveryBoyController::class,'delivery_boy_configure'])->name('delivery-boy-configuration');
    Route::post('/delivery-boy/order-collection', [DeliveryBoyController::class,'order_collection_form'])->name('delivery-boy.order-collection');
    Route::post('/collection-from-delivery-boy', [DeliveryBoyController::class,'collection_from_delivery_boy'])->name('collection-from-delivery-boy');
    Route::post('/delivery-boy/delivery-earning', [DeliveryBoyController::class,'delivery_earning_form'])->name('delivery-boy.delivery-earning');
    Route::post('/paid-to-delivery-boy', [DeliveryBoyController::class,'paid_to_delivery_boy'])->name('paid-to-delivery-boy');
    Route::get('/delivery-boys-payment-histories', [DeliveryBoyController::class,'delivery_boys_payment_histories'])->name('delivery-boys-payment-histories');
    Route::get('/delivery-boys-collection-histories', [DeliveryBoyController::class,'delivery_boys_collection_histories'])->name('delivery-boys-collection-histories');
    Route::get('/delivery-boy/cancel-request', [DeliveryBoyController::class,'cancel_request_list'])->name('delivery-boy.cancel-request');
    
});

Route::group(['middleware' => ['user', 'verified', 'unbanned']], function() {
    Route::get('/assigned-deliveries', [DeliveryBoyController::class,'assigned_delivery'])->name('assigned-deliveries');
    Route::get('/pickup-deliveries', [DeliveryBoyController::class,'pickup_delivery'])->name('pickup-deliveries');
    Route::get('/on-the-way-deliveries', [DeliveryBoyController::class,'on_the_way_deliveries'])->name('on-the-way-deliveries');
    Route::get('/completed-deliveries', [DeliveryBoyController::class,'completed_delivery'])->name('completed-deliveries');
    Route::get('/pending-deliveries', [DeliveryBoyController::class,'pending_delivery'])->name('pending-deliveries');
    Route::get('/cancelled-deliveries', [DeliveryBoyController::class,'cancelled_delivery'])->name('cancelled-deliveries');
    Route::get('/total-collections', [DeliveryBoyController::class,'total_collection'])->name('total-collection');
    Route::get('/total-earnings', [DeliveryBoyController::class,'total_earning'])->name('total-earnings');
    Route::get('/cancel-request/{id}', [DeliveryBoyController::class,'cancel_request'])->name('cancel-request');
    Route::get('/cancel-request-list', [DeliveryBoyController::class,'cancel_request_list'])->name('cancel-request-list');
});
