<?php

Route::get('/african/configuration', [AfricanPaymentGatewayController::class,'configuration'])->name('african.configuration');
Route::get('/african/credentials_index', [AfricanPaymentGatewayController::class,'credentials_index'])->name('african_credentials.index');

//Mpesa

Route::prefix('lnmo')->group(function ()
{
  Route::post('mpesa_pay', [MpesaController::class,'payment_complete'])->name('mpesa.pay');
  Route::any('pay', [MpesaController::class,'mpesa_pay']);
  Route::any('validate', [MpesaController::class,'validation']);
  Route::any('confirm', [MpesaController::class,'confirmation']);
  Route::any('results', [MpesaController::class,'results']);
  Route::any('register', [MpesaController::class,'register']);
  Route::any('timeout', [MpesaController::class,'timeout']);
  Route::any('reconcile', [MpesaController::class,'reconcile']);
});

//Mpesa End

// RaveController start
Route::get('/rave/callback', [FlutterwaveController::class,'callback'])->name('flutterwave.callback');

// RaveController end

//Payfast routes <starts>

Route::any('/payfast/checkout/notify', [PayfastController::class,'checkout_notify'])->name('payfast.checkout.notify');
Route::any('/payfast/checkout/return', [PayfastController::class,'checkout_return'])->name('payfast.checkout.return');
Route::any('/payfast/checkout/cancel', [PayfastController::class,'checkout_cancel'])->name('payfast.checkout.cancel');

Route::any('/payfast/wallet/notify', [PayfastController::class,'wallet_notify'])->name('payfast.wallet.notify');
Route::any('/payfast/wallet/return', [PayfastController::class,'wallet_return'])->name('payfast.wallet.return');
Route::any('/payfast/wallet/cancel', [PayfastController::class,'wallet_cancel'])->name('payfast.wallet.cancel');

Route::any('/payfast/seller_package_payment/notify', [PayfastController::class,'seller_package_notify'])->name('payfast.seller_package_payment.notify');
Route::any('/payfast/seller_package_payment/return', [PayfastController::class,'seller_package_payment_return'])->name('payfast.seller_package_payment.return');
Route::any('/payfast/seller_package_payment/cancel', [PayfastController::class,'seller_package_payment_cancel'])->name('payfast.seller_package_payment.cancel');

Route::any('/payfast/customer_package_payment/notify', [PayfastController::class,'customer_package_notify'])->name('payfast.customer_package_payment.notify');
Route::any('/payfast/customer_package_payment/return', [PayfastController::class,'customer_package_return'])->name('payfast.customer_package_payment.return');
Route::any('/payfast/customer_package_payment/cancel', [PayfastController::class,'customer_package_cancel'])->name('payfast.customer_package_payment.cancel');
//Payfast routes <ends>
