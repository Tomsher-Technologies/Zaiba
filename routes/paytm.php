<?php

//Paytm
Route::get('/paytm/index', [PaytmController::class,'index']);
Route::post('/paytm/callback', [PaytmController::class,'callback'])->name('paytm.callback');

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::get('/paytm_configuration', [PaytmController::class,'credentials_index'])->name('paytm.index');
    Route::post('/paytm_configuration_update', [PaytmController::class,'update_credentials'])->name('paytm.update_credentials');
});
