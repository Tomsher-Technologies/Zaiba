<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
// use App\Mail\SupportMailManager;
//demo

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Frontend\EnquiryContoller;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\WishlistController;
use App\Http\Livewire\Frontend\Cart;
use App\Http\Livewire\Frontend\Checkout;
use App\Models\Order;

Route::get('/demo/cron_1', [DemoController::class, 'cron_1']);
Route::get('/demo/cron_2', [DemoController::class, 'cron_2']);
Route::get('/convert_assets', [DemoController::class, 'convert_assets']);
Route::get('/convert_category', [DemoController::class, 'convert_category']);
Route::get('/convert_tax', [DemoController::class, 'convertTaxes']);
Route::get('/insert_product_variant_forcefully', [DemoController::class, 'insert_product_variant_forcefully']);
Route::get('/update_seller_id_in_orders/{id_min}/{id_max}', [DemoController::class, 'update_seller_id_in_orders']);
Route::get('/migrate_attribute_values', [DemoController::class, 'migrate_attribute_values']);

Route::get('/refresh-csrf', function () {
    return csrf_token();
});

Route::get('/test', function () {
    // return view('frontend.order_confirmed');
    $order = Order::find(1);


    // if ($order->user_id !== null) {
    //     Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
    // } else {
    //     $address = json_decode($order->shipping_address);
    //     if (isset($address->email)) {
    //         Mail::to($address->email)->queue(new InvoiceEmailManager($array));
    //     }
    // }

    // $array['view'] = 'emails.invoice';
    // $array['subject'] = translate('A new order has been placed') . ' - ' . $order->code;
    // $array['from'] = env('MAIL_FROM_ADDRESS');
    // $array['order'] = $order;

    new App\Notifications\PasswordReset("asd");

    // return new App\Mail\InvoiceEmailManager($array);
});

Auth::routes([
    'verify' => false,
    'reset' => true
]);
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::get('/verification-confirmation/{code}', 'Auth\VerificationController@verification_confirmation')->name('email.verification.confirmation');
Route::get('/email_change/callback', [HomeController::class, 'email_change_callback'])->name('email_change.callback');
// Route::post('/password/reset/email/submit', [HomeController::class, 'reset_password_with_code'])->name('password.update');


Route::post('/language', [LanguageController::class, 'changeLanguage'])->name('language.change');
Route::post('/currency', [CurrencyController::class, 'changeCurrency'])->name('currency.change');

Route::get('/social-login/redirect/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.login');
Route::get('/social-login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');
// Route::get('/signin', [HomeController::class, 'login'])->name('user.login');
Route::get('/registration', [HomeController::class, 'registration'])->name('user.registration');
//Route::post('/users/login', [HomeController::class,'user_login'])->name('user.login.submit');
Route::post('/signin/cart', [HomeController::class, 'cart_login'])->name('cart.login.submit');

//Home Page
// Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/home/section/brands', [HomeController::class, 'load_brands_section'])->name('home.section.brands');
Route::post('/home/section/large_banner', [HomeController::class, 'load_large_banner_section'])->name('home.section.large_banner');
// Route::post('/home/section/featured', [HomeController::class, 'load_featured_section'])->name('home.section.featured');
// Route::post('/home/section/best_selling', [HomeController::class, 'load_best_selling_section'])->name('home.section.best_selling');
// Route::post('/home/section/home_categories', [HomeController::class, 'load_home_categories_section'])->name('home.section.home_categories');
// Route::post('/home/section/best_sellers', [HomeController::class, 'load_best_sellers_section'])->name('home.section.best_sellers');
//category dropdown menu ajax call
Route::post('/category/nav-element-list', [HomeController::class, 'get_category_items'])->name('category.elements');

//Flash Deal Details Page
Route::get('/flash-deals', [HomeController::class, 'all_flash_deals'])->name('flash-deals');
Route::get('/flash-deal/{slug}', [HomeController::class, 'flash_deal_details'])->name('flash-deal-details');


Route::get('/sitemap.xml', function () {
    return base_path('sitemap.xml');
});


// Route::get('/customer-products', [CustomerProductController::class, 'customer_products_listing'])->name('customer.products');
// Route::get('/customer-products?category={category_slug}', [CustomerProductController::class, 'search'])->name('customer_products.category');
// Route::get('/customer-products?city={city_id}', [CustomerProductController::class, 'search'])->name('customer_products.city');
// Route::get('/customer-products?q={search}', [CustomerProductController::class, 'search'])->name('customer_products.search');
// Route::get('/customer-products/admin', [IyzicoController::class, 'initPayment'])->name('profile.edit');
// Route::get('/customer-product/{slug}', [CustomerProductController::class, 'customer_product'])->name('customer.product');
// Route::get('/customer-packages', [HomeController::class, 'premium_package_index'])->name('customer_packages_list_show');

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search?keyword={search}', [SearchController::class, 'index'])->name('suggestion.search');
Route::post('/ajax-search', [SearchController::class, 'ajax_search'])->name('search.ajax');
Route::get('/category/{category_slug}', [SearchController::class, 'listingByCategory'])->name('products.category');
Route::get('/brand/{brand_slug}', [SearchController::class, 'listingByBrand'])->name('products.brand');

// Quick view
Route::get('/product/quick_view', [HomeController::class, 'productQuickView'])->name('product.quick_view');
Route::post('/product/details/same_brand', [HomeController::class, 'productSameBrandView'])->name('product.details.same_brand');
Route::post('/product/details/related_products', [HomeController::class, 'productRelatedProductsView'])->name('product.details.related_products');
Route::post('/product/details/also_bought', [HomeController::class, 'productAlsoBoughtView'])->name('product.details.also_bought');
Route::get('/product/{slug}', [HomeController::class, 'product'])->name('product');
Route::post('/product/variant_price', [HomeController::class, 'variant_price'])->name('products.variant_price');
Route::get('/shop/{slug}', [HomeController::class, 'shop'])->name('shop.visit');
Route::get('/shop/{slug}/{type}', [HomeController::class, 'filter_shop'])->name('shop.visit.type');

Route::get('/cart', Cart::class)->name('cart');
// Route::post('/cart/show-cart-modal', [CartController::class, 'showCartModal'])->name('cart.showCartModal');
Route::post('/cart/addtocart', [CartController::class, 'addToCart'])->name('cart.addToCart');
Route::post('/cart/removeFromCart', [CartController::class, 'removeFromCart'])->name('cart.removeFromCart');
// Route::post('/cart/updateQuantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');


// 

//Checkout Routes
Route::group(['prefix' => 'checkout'], function () {
    Route::get('/', Checkout::class)->name('checkout.checkout_page');
    Route::any('/delivery_info', [CheckoutController::class, 'store_shipping_info'])->name('checkout.store_shipping_infostore');
    Route::post('/payment_select', [CheckoutController::class, 'store_delivery_info'])->name('checkout.store_delivery_info');
    Route::get('/shipping_methods', [CheckoutController::class, 'get_shipping_methods'])->name('checkout.shipping_methods');

    Route::get('/order-confirmed/{order}', [CheckoutController::class, 'order_confirmed'])->name('order_confirmed');
    Route::get('/order-failed/{order}', [CheckoutController::class, 'order_failed'])->name('order_failed');
    Route::get('/payment/{order}', [CheckoutController::class, 'checkout'])->name('payment.checkout');
    Route::post('/get_pick_up_points', [HomeController::class, 'get_pick_up_points'])->name('shipping_info.get_pick_up_points');
    Route::get('/payment-select', [CheckoutController::class, 'get_payment_info'])->name('checkout.payment_info');
    Route::post('/apply_coupon_code', [CheckoutController::class, 'apply_coupon_code'])->name('checkout.apply_coupon_code');
    Route::post('/remove_coupon_code', [CheckoutController::class, 'remove_coupon_code'])->name('checkout.remove_coupon_code');
    //Club point
    Route::post('/apply-club-point', [CheckoutController::class, 'apply_club_point'])->name('checkout.apply_club_point');
    Route::post('/remove-club-point', [CheckoutController::class, 'remove_club_point'])->name('checkout.remove_club_point');
});

Route::group(['prefix' => 'enquiry'], function () {
    Route::get('/', [EnquiryContoller::class, 'index'])->name('enquiry.index');
    Route::post('/', [EnquiryContoller::class, 'submit']);
    Route::post('/add', [EnquiryContoller::class, 'add'])->name('enquiry.add');
    Route::post('/remove', [EnquiryContoller::class, 'remove'])->name('enquiry.remove');
});

//Paypal START
Route::get('/paypal/payment/done', [PaypalController::class, 'getDone'])->name('payment.done');
Route::get('/paypal/payment/cancel', [PaypalController::class, 'getCancel'])->name('payment.cancel');
//Paypal END

//Mercadopago START
Route::any('/mercadopago/payment/done', [MercadopagoController::class, 'paymentstatus'])->name('mercadopago.done');
Route::any('/mercadopago/payment/cancel', [MercadopagoController::class, 'callback'])->name('mercadopago.cancel');
//Mercadopago 

// SSLCOMMERZ Start
Route::get('/sslcommerz/pay', [PublicSslCommerzPaymentController::class, 'index']);
Route::POST('/sslcommerz/success', [PublicSslCommerzPaymentController::class, 'success']);
Route::POST('/sslcommerz/fail', [PublicSslCommerzPaymentController::class, 'fail']);
Route::POST('/sslcommerz/cancel', [PublicSslCommerzPaymentController::class, 'cancel']);
Route::POST('/sslcommerz/ipn', [PublicSslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END
//Stipe Start
Route::get('stripe', [StripePaymentController::class, 'stripe']);
Route::post('/stripe/create-checkout-session', [StripePaymentController::class, 'create_checkout_session'])->name('stripe.get_token');
Route::any('/stripe/payment/callback', [StripePaymentController::class, 'callback'])->name('stripe.callback');
Route::get('/stripe/success', [StripePaymentController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripePaymentController::class, 'cancel'])->name('stripe.cancel');
//Stripe END

Route::get('/compare', [CompareController::class, 'index'])->name('compare');
Route::get('/compare/reset', [CompareController::class, 'reset'])->name('compare.reset');
Route::post('/compare/addToCompare', [CompareController::class, 'addToCompare'])->name('compare.addToCompare');

Route::resource('subscribers', SubscriberController::class);

Route::get('/brands', [HomeController::class, 'all_brands'])->name('brands.all');
Route::get('/categories', [HomeController::class, 'all_categories'])->name('categories.all');
Route::get('/sellers', [HomeController::class, 'all_seller'])->name('sellers');
Route::get('/coupons', [HomeController::class, 'all_coupons'])->name('coupons.all');
Route::get('/inhouse', [HomeController::class, 'inhouse_products'])->name('inhouse.all');

// Route::get('/seller-policy', [HomeController::class, 'sellerpolicy'])->name('sellerpolicy');
// Route::get('/return-policy', [HomeController::class, 'returnpolicy'])->name('returnpolicy');
// Route::get('/support-policy', [HomeController::class, 'supportpolicy'])->name('supportpolicy');
// Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
// Route::get('/privacy-policy', [HomeController::class, 'privacypolicy'])->name('privacypolicy');


Route::group(['middleware' => ['user']], function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::get('/profile/password', [HomeController::class, 'profilePassword'])->name('profile.password');
    Route::post('/profile/password', [HomeController::class, 'profilePasswordUpdate']);
    Route::post('/new-user-verification', [HomeController::class, 'new_verify'])->name('user.new.verify');
    Route::post('/new-user-email', [HomeController::class, 'update_email'])->name('user.change.email');

    Route::post('/user/update-profile', [HomeController::class, 'userProfileUpdate'])->name('user.profile.update');

    Route::resource('purchase_history', PurchaseHistoryController::class);
    Route::get('/purchase_history/details/{order_id}', [PurchaseHistoryController::class, 'purchase_history_details'])->name('purchase_history.details');
    Route::get('/purchase_history/destroy/{id}', [PurchaseHistoryController::class, 'destroy'])->name('purchase_history.destroy');

    Route::resource('wishlists', WishlistController::class);
    Route::post('/wishlists/remove', [WishlistController::class, 'remove'])->name('wishlists.remove');

    // Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    // Route::post('/recharge', [WalletController::class, 'recharge'])->name('wallet.recharge');

    Route::resource('support_ticket', 'SupportTicketController');
    Route::post('support_ticket/reply', [SupportTicketController::class, 'seller_store'])->name('support_ticket.seller_store');

    // Route::post('/customer_packages/purchase', [CustomerPackageController::class, 'purchase_package'])->name('customer_packages.purchase');
    // Route::resource('customer_products', 'CustomerProductController');
    // Route::get('/customer_products/{id}/edit', [CustomerProductController::class, 'edit'])->name('customer_products.edit');
    // Route::post('/customer_products/published', [CustomerProductController::class, 'updatePublished'])->name('customer_products.published');
    // Route::post('/customer_products/status', [CustomerProductController::class, 'updateStatus'])->name('customer_products.update.status');

    // Route::get('digital_purchase_history', [PurchaseHistoryController::class, 'digital_index'])->name('digital_purchase_history.index');

    Route::get('/all-notifications', [NotificationController::class, 'index'])->name('all-notifications');

    Route::resource('addresses', AddressController::class);
    Route::post('/addresses/update/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::get('/addresses/destroy/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/set_default', [AddressController::class, 'set_default'])->name('addresses.set_default');
});

Route::get('/customer_products/destroy/{id}', [CustomerProductController::class, 'destroy'])->name('customer_products.destroy');

Route::group(['prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user']], function () {
    Route::get('/products', [HomeController::class, 'seller_product_list'])->name('seller.products');
    Route::get('/product/upload', [HomeController::class, 'show_product_upload_form'])->name('seller.products.upload');
    Route::get('/product/{id}/edit', [HomeController::class, 'show_product_edit_form'])->name('seller.products.edit');
    Route::resource('payments', 'PaymentController');

    Route::get('/shop/apply_for_verification', [ShopController::class, 'verify_form'])->name('shop.verify');
    Route::post('/shop/apply_for_verification', [ShopController::class, 'verify_form_store'])->name('shop.verify.store');

    Route::get('/reviews', [ReviewController::class, 'seller_reviews'])->name('reviews.seller');

    //digital Product
    Route::get('/digitalproducts', [HomeController::class, 'seller_digital_product_list'])->name('seller.digitalproducts');
    Route::get('/digitalproducts/upload', [HomeController::class, 'show_digital_product_upload_form'])->name('seller.digitalproducts.upload');
    Route::get('/digitalproducts/{id}/edit', [HomeController::class, 'show_digital_product_edit_form'])->name('seller.digitalproducts.edit');

    //Coupon
    Route::get('/coupons', [CouponController::class, 'sellerIndex'])->name('seller.coupon.index');
    Route::get('/coupons/create', [CouponController::class, 'sellerCreate'])->name('seller.coupon.create');
    Route::post('/coupons/store', [CouponController::class, 'sellerStore'])->name('seller.coupon.store');
    Route::get('/coupon/edit/{id}', [CouponController::class, 'sellerEdit'])->name('seller.coupon.edit');
    Route::get('/coupon/destroy/{id}', [CouponController::class, 'sellerDestroy'])->name('seller.coupon.destroy');
    Route::patch('/coupons/update/{id}', [CouponController::class, 'sellerUpdate'])->name('seller.coupon.update');

    //Upload
    Route::any('/uploads', [AizUploadController::class, 'index'])->name('my_uploads.all');
    Route::any('/uploads/new', [AizUploadController::class, 'create'])->name('my_uploads.new');
    Route::any('/uploads/file-info', [AizUploadController::class, 'file_info'])->name('my_uploads.info');
    Route::get('/uploads/destroy/{id}', [AizUploadController::class, 'destroy'])->name('my_uploads.destroy');
});

Route::group(['middleware' => ['auth']], function () {
    // Route::post('/products/store/', [ProductController::class,'store'])->name('products.store');
    // Route::post('/products/update/{id}', [ProductController::class,'update'])->name('products.update');
    // Route::get('/products/destroy/{id}', [ProductController::class,'destroy'])->name('products.destroy');
    // Route::get('/products/duplicate/{id}', [ProductController::class,'duplicate'])->name('products.duplicate');
    // Route::post('/products/sku_combination', [ProductController::class,'sku_combination'])->name('products.sku_combination');
    // Route::post('/products/sku_combination_edit', [ProductController::class,'sku_combination_edit'])->name('products.sku_combination_edit');
    // Route::post('/products/seller/featured', [ProductController::class,'updateSellerFeatured'])->name('products.seller.featured');
    // Route::post('/products/published', [ProductController::class,'updatePublished'])->name('products.published');
    // Route::post('/products/add-more-choice-option', [ProductController::class,'add_more_choice_option'])->name('products.add-more-choice-option');

    Route::get('invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('invoice.download');

    Route::resource('orders', OrderController::class);
    Route::get('/orders/destroy/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/details', [OrderController::class, 'order_details'])->name('orders.details');
    Route::post('/orders/update_delivery_status', [OrderController::class, 'update_delivery_status'])->name('orders.update_delivery_status');
    Route::post('/orders/update_payment_status', [OrderController::class, 'update_payment_status'])->name('orders.update_payment_status');
    Route::post('/orders/update_tracking_code', [OrderController::class, 'update_tracking_code'])->name('orders.update_tracking_code');

    //Delivery Boy Assign
    Route::post('/orders/delivery-boy-assign', [OrderController::class, 'assign_delivery_boy'])->name('orders.delivery-boy-assign');

    // Route::resource('/reviews', ReviewController::class);

    Route::resource('/withdraw_requests', 'SellerWithdrawRequestController');
    Route::get('/withdraw_requests_all', [SellerWithdrawRequestController::class, 'request_index'])->name('withdraw_requests_all');
    Route::post('/withdraw_request/payment_modal', [SellerWithdrawRequestController::class, 'payment_modal'])->name('withdraw_request.payment_modal');
    Route::post('/withdraw_request/message_modal', [SellerWithdrawRequestController::class, 'message_modal'])->name('withdraw_request.message_modal');

    Route::resource('conversations', 'ConversationController');
    Route::get('/conversations/destroy/{id}', [ConversationController::class, 'destroy'])->name('conversations.destroy');
    Route::post('conversations/refresh', [ConversationController::class, 'refresh'])->name('conversations.refresh');
    Route::resource('messages', 'MessageController');

    Route::resource('digitalproducts', 'DigitalProductController');
    Route::get('/digitalproducts/edit/{id}', [DigitalProductController::class, 'edit'])->name('digitalproducts.edit');
    Route::get('/digitalproducts/destroy/{id}', [DigitalProductController::class, 'destroy'])->name('digitalproducts.destroy');
    Route::get('/digitalproducts/download/{id}', [DigitalProductController::class, 'download'])->name('digitalproducts.download');

    //Reports
    // Route::get('/commission-log', [ReportController::class,'commission_history'])->name('commission-log.index');

    //Coupon Form

});

Route::resource('shops', 'ShopController');
Route::get('/track-your-order', [HomeController::class, 'trackOrder'])->name('orders.track');

Route::get('/instamojo/payment/pay-success', [InstamojoController::class, 'success'])->name('instamojo.success');

Route::post('rozer/payment/pay-success', [RazorpayController::class, 'payment'])->name('payment.rozer');

Route::get('/paystack/payment/callback', [PaystackController::class, 'handleGatewayCallback']);

Route::get('/vogue-pay', [VoguePayController::class, 'showForm']);
Route::get('/vogue-pay/success/{id}', [VoguePayController::class, 'paymentSuccess']);
Route::get('/vogue-pay/failure/{id}', [VoguePayController::class, 'paymentFailure']);

//Iyzico
Route::any('/iyzico/payment/callback/{payment_type}/{amount?}/{payment_method?}/{combined_order_id?}/{customer_package_id?}/{seller_package_id?}', [IyzicoController::class, 'callback'])->name('iyzico.callback');

//Address
Route::post('/get-city', [CityController::class, 'get_city'])->name('get-city');
Route::post('/get-states', [AddressController::class, 'getStates'])->name('get-state');
Route::post('/get-cities', [AddressController::class, 'getCities'])->name('get-city');

//payhere below
Route::get('/payhere/checkout/testing', [PayhereController::class, 'checkout_testing'])->name('payhere.checkout.testing');
Route::get('/payhere/wallet/testing', [PayhereController::class, 'wallet_testing'])->name('payhere.checkout.testing');
Route::get('/payhere/customer_package/testing', [PayhereController::class, 'customer_package_testing'])->name('payhere.customer_package.testing');

Route::any('/payhere/checkout/notify', [PayhereController::class, 'checkout_notify'])->name('payhere.checkout.notify');
Route::any('/payhere/checkout/return', [PayhereController::class, 'checkout_return'])->name('payhere.checkout.return');
Route::any('/payhere/checkout/cancel', [PayhereController::class, 'chekout_cancel'])->name('payhere.checkout.cancel');

Route::any('/payhere/wallet/notify', [PayhereController::class, 'wallet_notify'])->name('payhere.wallet.notify');
Route::any('/payhere/wallet/return', [PayhereController::class, 'wallet_return'])->name('payhere.wallet.return');
Route::any('/payhere/wallet/cancel', [PayhereController::class, 'wallet_cancel'])->name('payhere.wallet.cancel');

Route::any('/payhere/seller_package_payment/notify', [PayhereController::class, 'seller_package_notify'])->name('payhere.seller_package_payment.notify');
Route::any('/payhere/seller_package_payment/return', [PayhereController::class, 'seller_package_payment_return'])->name('payhere.seller_package_payment.return');
Route::any('/payhere/seller_package_payment/cancel', [PayhereController::class, 'seller_package_payment_cancel'])->name('payhere.seller_package_payment.cancel');

Route::any('/payhere/customer_package_payment/notify', [PayhereController::class, 'customer_package_notify'])->name('payhere.customer_package_payment.notify');
Route::any('/payhere/customer_package_payment/return', [PayhereController::class, 'customer_package_return'])->name('payhere.customer_package_payment.return');
Route::any('/payhere/customer_package_payment/cancel', [PayhereController::class, 'customer_package_cancel'])->name('payhere.customer_package_payment.cancel');

//N-genius
Route::any('ngenius/cart_payment_callback', [NgeniusController::class, 'cart_payment_callback'])->name('ngenius.cart_payment_callback');
Route::any('ngenius/wallet_payment_callback', [NgeniusController::class, 'wallet_payment_callback'])->name('ngenius.wallet_payment_callback');
Route::any('ngenius/customer_package_payment_callback', [NgeniusController::class, 'customer_package_payment_callback'])->name('ngenius.customer_package_payment_callback');
Route::any('ngenius/seller_package_payment_callback', [NgeniusController::class, 'seller_package_payment_callback'])->name('ngenius.seller_package_payment_callback');

//bKash
Route::post('/bkash/createpayment', [BkashController::class, 'checkout'])->name('bkash.checkout');
Route::post('/bkash/executepayment', [BkashController::class, 'excecute'])->name('bkash.excecute');
Route::get('/bkash/success', [BkashController::class, 'success'])->name('bkash.success');

//Nagad
Route::get('/nagad/callback', [NagadController::class, 'verify'])->name('nagad.callback');

//aamarpay
Route::post('/aamarpay/success', [AamarpayController::class, 'success'])->name('aamarpay.success');
Route::post('/aamarpay/fail', [AamarpayController::class, 'fail'])->name('aamarpay.fail');

//Authorize-Net-Payment
Route::post('/dopay/online', [AuthorizeNetController::class, 'handleonlinepay'])->name('dopay.online');

//payku
Route::get('/payku/callback/{id}', [PaykuController::class, 'callback'])->name('payku.result');

//Blog Section
Route::get('/blog', [BlogController::class, 'all_blog'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'blog_details'])->name('blog.details');


//mobile app balnk page for webview
Route::get('/mobile-page/{slug}', [PageController::class, 'mobile_custom_page'])->name('mobile.custom-pages');

//Custom page
Route::get('/{slug}', [PageController::class, 'show_custom_page'])->name('custom-pages.show_custom_page');
