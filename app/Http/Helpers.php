<?php

use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\CommissionController;
use App\Models\Currency;
use App\Models\BusinessSetting;
use App\Models\ProductStock;
use App\Models\Address;
use App\Models\CustomerPackage;
use App\Models\Upload;
use App\Models\Translation;
use App\Models\City;
use App\Utility\CategoryUtility;
use App\Models\Wallet;
use App\Models\CombinedOrder;
use App\Models\User;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Products\ProductEnquiries;
use App\Models\Shop;
use App\Models\Wishlist;
use App\Utility\SendSMSUtility;
use App\Utility\NotificationUtility;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Harimayco\Menu\Facades\Menu;

//sensSMS function for OTP
if (!function_exists('sendSMS')) {
    function sendSMS($to, $from, $text, $template_id)
    {
        return SendSMSUtility::sendSMS($to, $from, $text, $template_id);
    }
}

//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active")
    {
        return in_array(Route::currentRouteName(), $routes) ? $output : '';
        // foreach ($routes as $route) {
        //     return Route::currentRouteName() == $route ? $output : '';
        // }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('areActiveRoutesHome')) {
    function areActiveRoutesHome(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('default_language')) {
    function default_language()
    {
        return env("DEFAULT_LANGUAGE");
    }
}

/**
 * Save JSON File
 * @return Response
 */
if (!function_exists('convert_to_usd')) {
    function convert_to_usd($amount)
    {
        $currency = Currency::find(get_setting('system_default_currency'));
        return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'USD')->first()->exchange_rate;
    }
}

if (!function_exists('convert_to_kes')) {
    function convert_to_kes($amount)
    {
        $currency = Currency::find(get_setting('system_default_currency'));
        return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'KES')->first()->exchange_rate;
    }
}

//filter products based on vendor activation system
if (!function_exists('filter_products')) {
    function filter_products($products)
    {
        $verified_sellers = verified_sellers_id();
        if (get_setting('vendor_system_activation') == 1) {
            return $products->where('approved', '1')->where('published', '1')->where('auction_product', 0)->orderBy('created_at', 'desc')->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            return $products->where('published', '1')->where('auction_product', 0)->where('added_by', 'admin');
        }
    }
}

//cache products based on category
if (!function_exists('get_cached_products')) {
    function get_cached_products($category_id = null)
    {
        $products = \App\Models\Product::where('published', 1)->where('approved', '1')->where('auction_product', 0);
        $verified_sellers = verified_sellers_id();
        if (get_setting('vendor_system_activation') == 1) {
            $products = $products->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            $products = $products->where('added_by', 'admin');
        }

        if ($category_id != null) {
            return Cache::remember('products-category-' . $category_id, 86400, function () use ($category_id, $products) {
                $category_ids = CategoryUtility::children_ids($category_id);
                $category_ids[] = $category_id;
                return $products->whereIn('category_id', $category_ids)->latest()->take(12)->get();
            });
        } else {
            return Cache::remember('products', 86400, function () use ($products) {
                return $products->latest()->take(12)->get();
            });
        }
    }
}

if (!function_exists('verified_sellers_id')) {
    function verified_sellers_id()
    {
        return Cache::rememberForever('verified_sellers_id', function () {
            return App\Models\Seller::where('verification_status', 1)->pluck('user_id')->toArray();
        });
    }
}

if (!function_exists('get_system_default_currency')) {
    function get_system_default_currency()
    {
        return Cache::remember('system_default_currency', 86400, function () {
            return Currency::findOrFail(get_setting('system_default_currency'));
        });
    }
}

//converts currency to home default currency
if (!function_exists('convert_price')) {
    function convert_price($price)
    {
        if (Session::has('currency_code') && (Session::get('currency_code') != get_system_default_currency()->code)) {
            $price = floatval($price) / floatval(get_system_default_currency()->exchange_rate);
            $price = floatval($price) * floatval(Session::get('currency_exchange_rate'));
        }
        return $price;
    }
}

//gets currency symbol
if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        if (Session::has('currency_symbol')) {
            return Session::get('currency_symbol');
        }
        return get_system_default_currency()->symbol;
    }
}

//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {
        if (get_setting('decimal_separator') == 1) {
            $fomated_price = number_format($price, get_setting('no_of_decimals'));
        } else {
            $fomated_price = number_format($price, get_setting('no_of_decimals'), ',', ' ');
        }

        if (get_setting('symbol_format') == 1) {
            return currency_symbol() . $fomated_price;
        } else if (get_setting('symbol_format') == 3) {
            return currency_symbol() . ' ' . $fomated_price;
        } else if (get_setting('symbol_format') == 4) {
            return $fomated_price . ' ' . currency_symbol();
        }
        return $fomated_price . currency_symbol();
    }
}

//formats price to home default price with convertion
if (!function_exists('single_price')) {
    function single_price($price)
    {
        return format_price(convert_price($price));
    }
}

if (!function_exists('discount_in_percentage')) {
    function discount_in_percentage($product)
    {
        try {
            $base = home_base_price($product, false);
            $reduced = home_discounted_base_price($product, false);
            $discount = $base - $reduced;

            if ($base > 0) {
                $dp = ($discount * 100) / $base;
                return round($dp);
            }
        } catch (Exception $e) {
            return 0;
        }
        return 0;
    }
}

//Shows Price on page based on low to high
if (!function_exists('home_price')) {
    function home_price($product, $formatted = true)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        if ($formatted) {
            if ($lowest_price == $highest_price) {
                return format_price(convert_price($lowest_price));
            } else {
                return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
            }
        } else {
            return $lowest_price . ' - ' . $highest_price;
        }
    }
}

//Shows Price on page based on low to high with discount
if (!function_exists('home_discounted_price')) {
    function home_discounted_price($product, $formatted = true)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $lowest_price -= ($lowest_price * $product->discount) / 100;
                $highest_price -= ($highest_price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $lowest_price -= $product->discount;
                $highest_price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        if ($formatted) {
            if ($lowest_price == $highest_price) {
                return format_price(convert_price($lowest_price));
            } else {
                return format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price));
            }
        } else {
            return $lowest_price . ' - ' . $highest_price;
        }
    }
}

//Shows Base Price
if (!function_exists('home_base_price_by_stock_id')) {
    function home_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $price = $product_stock->price;
        $tax = 0;

        foreach ($product_stock->product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return format_price(convert_price($price));
    }
}
if (!function_exists('home_base_price')) {
    function home_base_price($product, $formatted = true)
    {
        $price = $product->unit_price;
        // $tax = 0;

        // foreach ($product->taxes as $product_tax) {
        //     if ($product_tax->tax_type == 'percent') {
        //         $tax += ($price * $product_tax->tax) / 100;
        //     } elseif ($product_tax->tax_type == 'amount') {
        //         $tax += $product_tax->tax;
        //     }
        // }
        // $price += $tax;
        return $formatted ? format_price(convert_price($price)) : $price;
    }
}

//Shows Base Price with discount
if (!function_exists('home_discounted_base_price_by_stock_id')) {
    function home_discounted_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $product = $product_stock->product;
        $price = $product_stock->price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;

        return format_price(convert_price($price));
    }
}

//Shows Base Price with discount
if (!function_exists('home_discounted_base_price')) {
    function home_discounted_base_price($product, $formatted = true)
    {
        $price = $product->unit_price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        // foreach ($product->taxes as $product_tax) {
        //     if ($product_tax->tax_type == 'percent') {
        //         $tax += ($price * $product_tax->tax) / 100;
        //     } elseif ($product_tax->tax_type == 'amount') {
        //         $tax += $product_tax->tax;
        //     }
        // }
        // $price += $tax;

        return $formatted ? format_price(convert_price($price)) : $price;
    }
}

if (!function_exists('renderStarRating')) {
    function renderStarRating($rating)
    {
        if ($rating == 0) {
            return null;
        }

        $html = '<div class="ps-product__rating"><select class="ps-rating" data-read-only="true">';
        for ($i = 1; $i <= 5; $i++) {
            $value = $i <= $rating ? 1 : 2;
            $html .= '<option value="' . $value . '">' . $i . '</option>';
        }
        $html .=  '</select><span>' . $rating . '</span></div>';
        echo $html;
    }
}

function translate($key, $lang = null, $addslashes = false)
{
    return $key;
    if ($lang == null) {
        $lang = App::getLocale();
    }

    $lang_key = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', strtolower($key)));

    $translations_en = Cache::rememberForever('translations-en', function () {
        return Translation::where('lang', 'en')->pluck('lang_value', 'lang_key')->toArray();
    });

    if (!isset($translations_en[$lang_key])) {
        $translation_def = new Translation;
        $translation_def->lang = 'en';
        $translation_def->lang_key = $lang_key;
        $translation_def->lang_value = str_replace(array("\r", "\n", "\r\n"), "", $key);
        $translation_def->save();
        Cache::forget('translations-en');
    }

    // return user session lang
    $translation_locale = Cache::rememberForever("translations-{$lang}", function () use ($lang) {
        return Translation::where('lang', $lang)->pluck('lang_value', 'lang_key')->toArray();
    });
    if (isset($translation_locale[$lang_key])) {
        return trim($translation_locale[$lang_key]);
    }

    // return default lang if session lang not found
    $translations_default = Cache::rememberForever('translations-' . env('DEFAULT_LANGUAGE', 'en'), function () {
        return Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'))->pluck('lang_value', 'lang_key')->toArray();
    });
    if (isset($translations_default[$lang_key])) {
        return trim($translations_default[$lang_key]);
    }

    // fallback to en lang
    if (!isset($translations_en[$lang_key])) {
        return trim($key);
    }
    return trim($translations_en[$lang_key]);
}

function remove_invalid_charcaters($str)
{
    $str = str_ireplace(array("\\"), '', $str);
    return str_ireplace(array('"'), '\"', $str);
}

function getShippingCost2($carts, $index)
{
    $admin_products = array();
    $seller_products = array();

    $cartItem = $carts[$index];
    $product = Product::find($cartItem['product_id']);

    if ($product->digital == 1) {
        return 0;
    }

    foreach ($carts as $key => $cart_item) {
        $item_product = Product::find($cart_item['product_id']);
        if ($item_product->added_by == 'admin') {
            array_push($admin_products, $cart_item['product_id']);
        } else {
            $product_ids = array();
            if (isset($seller_products[$item_product->user_id])) {
                $product_ids = $seller_products[$item_product->user_id];
            }
            array_push($product_ids, $cart_item['product_id']);
            $seller_products[$item_product->user_id] = $product_ids;
        }
    }

    if (get_setting('shipping_type') == 'flat_rate') {
        return get_setting('flat_rate_shipping_cost') / count($carts);
    } elseif (get_setting('shipping_type') == 'seller_wise_shipping') {
        if ($product->added_by == 'admin') {
            return get_setting('shipping_cost_admin') / count($admin_products);
        } else {
            return Shop::where('user_id', $product->user_id)
                ->first()
                ->shipping_cost / count($seller_products[$product->user_id]);
        }
    } elseif (get_setting('shipping_type') == 'area_wise_shipping') {
        $shippingInfo = Address::where('id', $carts[0]['address_id'])->first();
        $city = City::where('id', $shippingInfo->city_id)->first();
        if ($city != null) {
            if ($product->added_by == 'admin') {
                return $city->cost / count($admin_products);
            } else {
                return $city->cost / count($seller_products[$product->user_id]);
            }
        }
        return 0;
    } else {
        if ($product->is_quantity_multiplied && get_setting('shipping_type') == 'product_wise_shipping') {
            return  $product->shipping_cost * $cartItem['quantity'];
        }
        return $product->shipping_cost;
    }
}

function timezones()
{
    return Timezones::timezonesToArray();
}

if (!function_exists('app_timezone')) {
    function app_timezone()
    {
        return config('app.timezone');
    }
}

if (!function_exists('api_asset')) {
    function api_asset($id)
    {
        if (($asset = \App\Models\Upload::find($id)) != null) {
            return $asset->file_name;
        }
        return "";
    }
}

//return file uploaded via uploader
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        if ($id && ($asset = \App\Models\Upload::find($id)) != null) {
            return $asset->external_link == null ? storage_asset($asset->file_name) : $asset->external_link;
        }

        return frontendAsset('img/placeholder.webp');;
    }
}

//return file uploaded via uploader with name
if (!function_exists('uploaded_asset_with_name')) {
    function uploaded_asset_with_name($id)
    {
        if ($id && ($asset = \App\Models\Upload::find($id)) != null) {
            return array(
                'link' => $asset->external_link == null ? storage_asset($asset->file_name) : $asset->external_link,
                'name' => $asset->file_original_name
            );
        }

        return null;
    }
}

if (!function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return Storage::disk('s3')->url($path);
        } else {
            return app('url')->asset('public/' . $path, $secure);
        }
    }
}

if (!function_exists('storage_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function storage_asset($path, $secure = null)
    {
        return app('url')->asset('storage/' . $path, $secure);
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        return app('url')->asset('admin_assets/' . $path, $secure);
    }
}

if (!function_exists('frontendAsset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function frontendAsset($path, $secure = null)
    {
        return app('url')->asset('assets/' . $path, $secure);
    }
}


// if (!function_exists('isHttps')) {
//     function isHttps()
//     {
//         return !empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']);
//     }
// }

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        $root = '//' . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root;
    }
}


if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return env('AWS_URL') . '/';
        } else {
            return app('url')->asset('storage') . '/';
            // return getBaseURL();
        }
    }
}


if (!function_exists('isUnique')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function isUnique($email)
    {
        $user = \App\Models\User::where('email', $email)->first();

        if ($user == null) {
            return '1'; // $user = null means we did not get any match with the email provided by the user inside the database
        } else {
            return '0';
        }
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $settings = Cache::remember('business_settings', 86400, function () {
            return BusinessSetting::select(['type', 'value'])->get()->keyBy('type')->toArray();
            // return BusinessSetting::select(['type', 'value'])->get()->toArray();
        });

        if (isset($settings[$key])) {
            return $settings[$key]['value'];
        }

        return $default;
        // $setting = $settings->where('type', $key)->first();
        // return $setting == null ? $default : $setting->value;
    }
}

function hex2rgba($color, $opacity = false)
{
    return Colorcodeconverter::convertHexToRgba($color, $opacity);
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {
            return true;
        }
        return false;
    }
}

if (!function_exists('isSeller')) {
    function isSeller()
    {
        if (Auth::check() && Auth::user()->user_type == 'seller') {
            return true;
        }
        return false;
    }
}

if (!function_exists('isCustomer')) {
    function isCustomer()
    {
        if (Auth::check() && Auth::user()->user_type == 'customer') {
            return true;
        }
        return false;
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

// duplicates m$ excel's ceiling function
if (!function_exists('ceiling')) {
    function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
    }
}

if (!function_exists('get_images')) {
    function get_images($given_ids, $with_trashed = false)
    {
        if (is_array($given_ids)) {
            $ids = $given_ids;
        } elseif ($given_ids == null) {
            $ids = [];
        } else {
            $ids = explode(",", $given_ids);
        }


        return $with_trashed
            ? Upload::withTrashed()->whereIn('id', $ids)->get()
            : Upload::whereIn('id', $ids)->get();
    }
}

//for api
if (!function_exists('get_images_path')) {
    function get_images_path($given_ids, $with_trashed = false)
    {
        $paths = [];
        $images = get_images($given_ids, $with_trashed);
        if (!$images->isEmpty()) {
            foreach ($images as $image) {
                $paths[] = !is_null($image) ? $image->file_name : "";
            }
        }

        return $paths;
    }
}

//for api
if (!function_exists('checkout_done')) {
    function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::find($combined_order_id);

        foreach ($combined_order->orders as $key => $order) {
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            try {
                NotificationUtility::sendOrderPlacedNotification($order);
                // calculateCommissionAffilationClubPoint($order);
            } catch (\Exception $e) {
                // Do nothing
            }
        }
    }
}

//for api
if (!function_exists('wallet_payment_done')) {
    function wallet_payment_done($user_id, $amount, $payment_method, $payment_details)
    {
        $user = \App\Models\User::find($user_id);
        $user->balance = $user->balance + $amount;
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $amount;
        $wallet->payment_method = $payment_method;
        $wallet->payment_details = $payment_details;
        $wallet->save();
    }
}

if (!function_exists('purchase_payment_done')) {
    function purchase_payment_done($user_id, $package_id)
    {
        $user = User::findOrFail($user_id);
        $user->customer_package_id = $package_id;
        $customer_package = CustomerPackage::findOrFail($package_id);
        $user->remaining_uploads += $customer_package->product_upload;
        $user->save();

        return 'success';
    }
}

//Commission Calculation
if (!function_exists('calculateCommissionAffilationClubPoint')) {
    function calculateCommissionAffilationClubPoint($order)
    {
        (new CommissionController)->calculateCommission($order);

        if (addon_is_activated('affiliate_system')) {
            (new AffiliateController)->processAffiliatePoints($order);
        }

        if (addon_is_activated('club_point')) {
            if ($order->user != null) {
                (new ClubPointController)->processClubPoints($order);
            }
        }

        $order->commission_calculated = 1;
        $order->save();
    }
}

// Addon Activation Check
if (!function_exists('addon_is_activated')) {
    function addon_is_activated($identifier, $default = null)
    {
        $addons = Cache::remember('addons', 86400, function () {
            return Addon::all();
        });

        $activation = $addons->where('unique_identifier', $identifier)->where('activated', 1)->first();
        return $activation == null ? false : true;
    }
}

// Get Image From Uploads
if (!function_exists('get_uploads_image')) {
    function get_uploads_image($relation)
    {
        if ($relation) {
            return storage_asset($relation->file_name);
        }

        return frontendAsset('img/placeholder.webp');
    }
}

// Get Image From Uploads
if (!function_exists('get_product_image')) {
    function get_product_image($path, $size = 'full')
    {
        if ($path) {
            if ($size == 'full') {
                return app('url')->asset($path);
            } else {
                $fileName = pathinfo($path)['filename'];
                $ext   = pathinfo($path)['extension'];
                $dirname   = pathinfo($path)['dirname'];
                $r_path = "{$dirname}/" . $fileName . "_{$size}px" . ".{$ext}";
                return app('url')->asset($r_path);
            }
        }

        return frontendAsset('img/placeholder.webp');
    }
}

// Load SEO details
if (!function_exists('load_seo_tags')) {
    function load_seo_tags($seo = null, $image = '')
    {
        if ($image == '') {
            $image = frontendAsset('img/logo_new.webp');
        }

        if ($seo) {
            SEOMeta::setTitle($seo->meta_title);
            SEOMeta::setDescription($seo->meta_description);
            SEOMeta::setKeywords($seo->meta_keywords);

            OpenGraph::setTitle($seo->og_title);
            OpenGraph::setDescription($seo->og_title);

            OpenGraph::addProperty('type', 'articles')
                ->addImage($image)
                ->setTitle($seo->og_title)
                ->setDescription($seo->og_description)
                ->setSiteName(env('APP_NAME', 'Industry Tech Store'));

            TwitterCard::setType('summary_large_image')
                ->setImage($image)
                ->setTitle($seo->twitter_title)
                ->setDescription($seo->twitter_description)
                ->setSite('@ind');

            JsonLd::setImage($image)
                ->setTitle($seo->meta_title)
                ->setDescription($seo->meta_description)
                ->setSite(env('APP_NAME', 'Industry Tech Store'));

            JsonLdMulti::setImage($image)
                ->setTitle($seo->meta_title)
                ->setDescription($seo->meta_description)
                ->setSite(env('APP_NAME', 'Industry Tech Store'));
        }
    }

    function getTempUserId()
    {
        if (Session::has('temp_user_id')) {
            $user_id = Session::get('temp_user_id');
        } else {
            $user_id = bin2hex(random_bytes(10));
            Session::put('temp_user_id', $user_id);
        }
        return $user_id;
    }

    function getAllCategories()
    {
        return Cache::rememberForever('categoriesTree', function () {
            return CategoryUtility::getSidebarCategoryTree();
        });
    }

    function wishListCount(): int
    {
        if (Auth::check()) {
            return Cache::remember('user_wishlist_count_' . Auth::id(), '3600', function () {
                return Wishlist::where('user_id', Auth::user()->id)->count();
            });
        }

        return 0;
    }

    function cartCount(): int
    {
        if (Auth::check()) {
            return Cache::remember('user_cart_count_' . Auth::id(), '3600', function () {
                return Cart::where('user_id', Auth::user()->id)->count();
            });
        } else {
            return Cache::remember('user_cart_count_' . getTempUserId(), '3600', function () {
                return Cart::where('temp_user_id', getTempUserId())->count();
            });
        }
    }

    function enquiryCount(): int
    {
        if (Auth::check()) {
            $user_col = "user_id";
            $user_id = Auth::id();
        } else {
            $user_col = "temp_user_id";
            $user_id = getTempUserId();
        }

        return Cache::remember('user_enquiry_count_' . $user_id, '3600', function () use ($user_col, $user_id) {
            $enquiries = ProductEnquiries::whereStatus(0)->where($user_col, $user_id)->withCount('products')->latest()->first();
            if ($enquiries) {
                return $enquiries->products_count;
            }
            return 0;
        });
    }

    function formatDate($date): String
    {
        if ($date->lessThan(Carbon::now()->subHours(12))) {
            return $date->format('d F, Y');
        }
        return $date->diffForHumans();
    }

    function deliveryBadge($status)
    {
        $html = '';

        switch ($status) {
            case 'pending':
                $html = '<span class="badge badge badge-soft-danger">Pending</span>';
                break;
            case 'confirmed':
                $html = '<span class="badge badge-soft-warning">Confirmed</span>';
                break;
            case 'picked_up':
                $html = '<span class="badge badge-soft-warning">Picked Up</span>';
                break;
            case 'on_the_way':
                $html = '<span class="badge badge-soft-warning">On The Way</span>';
                break;
            case 'delivered':
                $html = '<span class="badge badge-soft-success">Delivered</span>';
                break;
            default:
                $html = '-';
                break;
        }

        return $html;
    }

    function getDeliveryStatusText($status)
    {
        return Str::title(str_replace('_', ' ', $status));
    }

    function getCurrentCurrency()
    {
        if (Session::has('currency_code')) {
            return Currency::where('code', Session::get('currency_code'))->first();
        } else {
            return Currency::find(get_setting('system_default_currency'));
        }
    }

    function getMenu($id)
    {
        // Cache::forget('menu_6');
        return Cache::rememberForever('menu_' . $id,  function () use ($id) {
            $menu = Menu::get($id);
            $menu_real = array();
            foreach ($menu as $key => $m) {
                $menu_real[$key] = $m;
                if ($m['img_1']) {
                    $menu_real[$key]['img_1_src'] = uploaded_asset($m['img_1']);
                }
                if ($m['img_2']) {
                    $menu_real[$key]['img_2_src'] = uploaded_asset($m['img_2']);
                }
                if ($m['img_3']) {
                    $menu_real[$key]['img_3_src'] = uploaded_asset($m['img_3']);
                }

                if ($m['brands'] !== null) {
                    $brand_ids = explode(',', $m['brands']);
                    $brands = Brand::whereIn('id', $brand_ids)->select(['id', 'name', 'logo', 'slug'])->with('logoImage', function ($query) {
                        return $query->select(['id', 'file_name']);
                    })->get();

                    $menu_real[$key]['brands'] = $brands;
                }
            }
            return $menu_real;
        });
    }

    function allProducts()
    {
        return Product::wherePublished(1)->latest()->get();
    }

    function getCurrency()
    {
        return Cache::rememberForever('currency', function () {
            return Currency::where('status', 1)->get();
        });
    }

    function getSeoValues($seo, $name)
    {
        if ($name && $seo) {
            return $seo->$name;
        }
        return "";
    }

    function deleteImage($path)
    {
        $fileName = 'public' . Str::remove('/storage', $path);
        if (Storage::exists($fileName)) {
            Storage::delete($fileName);
        }
    }

    function cleanSKU($sku)
    {
        $sku = str_replace(' ', '', $sku);
        $sku = preg_replace('/[^a-zA-Z0-9_-]/', '', $sku);
        return $sku;
    }

    function userHasPermision($id)
    {
        if (Auth::user()->user_type == 'admin' || in_array($id, json_decode(Auth::user()->staff->role->permissions))) {
            return true;
        }
        return false;
    }

    // function testView()
    // {
    //     Cache::forget('awesomeHtml');
    //     $html = Cache::remember('awesomeHtml', 3600, function () {
    //         return view('frontend.inc.header-part.desktop-header')->render();
    //     });

    //     return $html;
    // }

    function generateOTP($user){
        $data['otp'] = rand(1000,9999);
        $data['otp_expiry'] = Carbon::now()->addMinutes(10);
        
        $user->otp = $data['otp'];
        $user->otp_expiry = $data['otp_expiry'];
        $user->save(); 

        return $data;
    }

    function sendOTP($data){
        $messages = urlencode($data['message']);
        $sender = urlencode("TOMSHER");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://tomsher.me/sms/smsapi?api_key=R60001345fd4c0b80cb815.29446877&type=text&contacts=971" . $data['phone'] . "&senderid=$sender&msg=$messages");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    function verifyUserOTP($user, $otp){
        $dbOtp = $user->otp;
        $otp_expiry = $user->otp_expiry;

        if($dbOtp === $otp && strtotime($otp_expiry) > time()) {
            $user->is_phone_verified = 1;
            $user->save();
            return true; // Verification successful
        }else{
            return false;
        }
    }

    function generateOTPMessage($userName, $otp){
        // $data['message'] = "Hello ".$user->name.",
        // Your One-Time Password (OTP) is: ".$otp.".
        // This OTP is valid for 10 minutes. For security reasons, do not share it with anyone.
        // Thank you for choosing ".env('APP_NAME').".";

        $message = "Hi ".$userName.", Greetings from Farook! Your OTP: ".$otp." Treat this as confidential. Sharing this with anyone gives them full access to your Farook Account.";
        return $message;
    }
}
