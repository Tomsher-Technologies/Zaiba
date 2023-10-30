<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\Brand;
use App\Models\Product;
use App\Models\PickupPoint;
use App\Models\CustomerPackage;
use App\Models\User;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\Order;
use App\Models\BusinessSetting;
use App\Models\Coupon;
use Cookie;
use Illuminate\Support\Str;
use App\Mail\SecondEmailVerifyMailManager;
use App\Models\Address;
use App\Models\AffiliateConfig;
use App\Models\Frontend\Banner;
use App\Models\Frontend\HomeSlider;
use App\Models\Page;
use App\Models\Upload;
use Mail;
use Illuminate\Auth\Events\PasswordReset;
use Cache;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Password;

class HomeController extends Controller
{
    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Cache::rememberForever('homeSlider', function () {
            $banners = HomeSlider::whereStatus(1)->with(['mainImage', 'mobileImage'])->orderBy('sort_order')->get();

            foreach ($banners as $banner) {
                $banner->a_link = $banner->getALink();
            }

            return $banners;
        });

        $small_banners = Cache::rememberForever('smallBanners', function () {
            $banners = get_setting('home_banner');

            if ($banners) {
                $banner = Banner::whereStatus(1)
                    ->whereIn('id', json_decode($banners))
                    ->with(['mainImage', 'mobileImage'])
                    ->get();

                foreach ($banner as $b) {
                    $b->a_link = $b->getALink();
                }

                return $banner;
            }
        });

        $ads_banners = Cache::rememberForever('ads_banners', function () {
            $banners = get_setting('home_ads_banner');
            if ($banners) {
                $banner = Banner::whereStatus(1)
                    ->whereIn('id', json_decode($banners))
                    ->with(['mainImage', 'mobileImage'])
                    ->get();

                foreach ($banner as $b) {
                    $b->a_link = $b->getALink();
                }

                return $banner;
            }
        });

        $trending_categories = Cache::rememberForever('trending_categories', function () {
            $categories = get_setting('home_categories');
            if ($categories) {
                return Category::whereIn('id', json_decode($categories))
                    ->with(['icon'])
                    ->get();
            }
        });

        $section_categories = Cache::rememberForever('section_categories', function () {
            $categories = get_setting('catsection_categories');
            if ($categories) {
                return Category::whereIn('id', json_decode($categories))
                    ->with(['icon'])
                    ->get();
            }
        });

        $cat_banners = Cache::rememberForever('cat_banners', function () {
            $banners = get_setting('cat_banner');
            if ($banners) {
                $banner = Banner::whereStatus(1)
                    ->whereIn('id', json_decode($banners))
                    ->with(['mainImage', 'mobileImage'])
                    ->get();

                foreach ($banner as $b) {
                    $b->a_link = $b->getALink();
                }

                return $banner;
            }
        });

        // dd($cat_banners);

        // $small_banners = Cache::rememberForever('adsBanners', function () {
        //     $banners = BusinessSetting::whereType('home_banner')->first();
        //     return Banner::whereStatus(1)
        //         ->whereIn('id', json_decode($banners->value))
        //         ->with(['mainImage', 'mobileImage'])
        //         ->get();
        // }); 

        // $featured_categories = Cache::rememberForever('featured_categories', function () {
        //     return Category::where('featured', 1)->get();
        // });

        // $todays_deal_products = Cache::rememberForever('todays_deal_products', function () {
        //     return filter_products(Product::where('published', 1)->where('todays_deal', '1'))->get();
        // });

        // Cache::forget('newest_products');

        $newest_products = Cache::remember('newest_products', 3600, function () {
            $product_ids = get_setting('latest_products');
            return Product::where('published', 1)->whereIn('id', json_decode($product_ids))->with('brand')->get();
        });

        $best_selling_products = Cache::remember('best_selling_products', 3600, function () {
            $product_ids = get_setting('best_selling');
            return Product::where('published', 1)->whereIn('id', json_decode($product_ids))->with('brand')->get();
        });

        // load_seo_tags(null, '', 'Home');

        return view('frontend.index', compact('sliders', 'small_banners', 'ads_banners', 'trending_categories', 'newest_products', 'best_selling_products', 'section_categories', 'cat_banners'));
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('frontend.auth.login');
    }

    public function registration(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        if ($request->has('referral_code') && addon_is_activated('affiliate_system')) {
            try {
                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if ($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }

                Cookie::queue('referral_code', $request->referral_code, $cookie_minute);
                $referred_by_user = User::where('referral_code', $request->product_referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            } catch (\Exception $e) {
            }
        }
        return view('frontend.user_registration');
    }

    public function cart_login(Request $request)
    {
        $user = null;
        if ($request->get('phone') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('phone', "+{$request['country_code']}{$request['phone']}")->first();
        } elseif ($request->get('email') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
        }

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                if ($request->has('remember')) {
                    auth()->login($user, true);
                } else {
                    auth()->login($user, false);
                }
            } else {
                flash(translate('Invalid email or password!'))->warning();
            }
        } else {
            flash(translate('Invalid email or password!'))->warning();
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::user()->user_type == 'customer') {
            $orders = Auth::user()->orders()->get();
            $total_orders = $orders->where('delivery_status', 'delivered')->count();
            $pending_orders = $orders->where('delivery_status', 'pending')->count();

            $default_address = Address::whereUserId(Auth::id())->whereSetDefault(1)->with([
                'country',
                'state',
                'city',
            ])->first();

            return view('frontend.user.dashboard')->with(compact('total_orders', 'pending_orders', 'default_address'));
        } else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        return view('frontend.user.profile');
    }

    public function profilePassword(Request $request)
    {
        return view('frontend.user.profilePassword');
    }

    public function profilePasswordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(6)
                    ->letters()
                    ->numbers()
                    ->uncompromised()
            ],
        ], [
            'current_password.required' => 'Please enter your current password',
            'password.required' => 'Please enter your new password',
            'password.confirmed' => 'Password and confirm password does not match',
            'password.different' => 'New password and old password cannot be same',
        ]);

        if (Hash::check($request->current_password, Auth::user()->password)) {

            Auth()->user()->update([
                'password' => Hash::make($request->password)
            ]);

            return back()->with([
                'status' => 'Your Profile has been updated successfully!'
            ]);
        }

        return back()->withErrors([
            'invalid' => 'Sorry, your current password does not match'
        ]);
    }

    public function userProfileUpdate(Request $request)
    {

        $request->validate([
            'name' => 'required'
        ], [
            'name.required' => 'Please enter your name'
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        // $user->address = $request->address;
        // $user->country = $request->country;
        // $user->city = $request->city;
        // $user->postal_code = $request->postal_code;
        // $user->phone = $request->phone;

        // if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
        //     $user->password = Hash::make($request->new_password);
        // }

        // $user->avatar_original = $request->photo;

        $user->save();

        return back()->with([
            'status' => 'Your Profile has been updated successfully!'
        ]);
    }

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
        if ($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function load_brands_section()
    {
        $brands = Cache::rememberForever('home_brands', function () {
            $brand_ids = get_setting('top10_brands');
            return Brand::whereIn('id', json_decode($brand_ids))->with('logoImage')->get();
        });
        return view('frontend.partials.home.brands_section', compact('brands'));
    }

    public function load_large_banner_section()
    {
        $large_banner = Cache::rememberForever('large_banner', function () {
            $banners = get_setting('home_large_banner');
            if ($banners) {
                return Banner::whereStatus(1)
                    ->whereIn('id', json_decode($banners))
                    ->with(['mainImage', 'mobileImage'])
                    ->first();
            }
        });
        return view('frontend.partials.home.large_banner_section', compact('large_banner'));
    }

    public function load_featured_section()
    {
        return view('frontend.partials.featured_products_section');
    }

    public function load_best_selling_section()
    {
        return view('frontend.partials.best_selling_section');
    }

    public function load_auction_products_section()
    {
        if (!addon_is_activated('auction')) {
            return;
        }
        return view('auction.frontend.auction_products_section');
    }

    public function load_home_categories_section()
    {
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section()
    {
        return view('frontend.partials.best_sellers_section');
    }

    public function trackOrder(Request $request)
    {
        if ($request->has('order_code')) {
            $order = Order::where('code', $request->order_code)->first();
            if ($order != null) {
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }

    public function product(Request $request, $slug)
    {
        $product = Product::with('reviews', 'reviews.user', 'brand', 'seo', 'category', 'tabs', 'stocks')->where('slug', $slug)->firstOrFail();
        $gallery = Upload::whereIn('id', explode(',', $product->photos))->get();
        load_seo_tags($product->seo);
        return view('frontend.product.product_details', compact('product', 'gallery'));
    }

    public function shop($slug)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null) {
            $seller = Seller::where('user_id', $shop->user_id)->first();
            if ($seller->verification_status != 0) {
                return view('frontend.seller_shop', compact('shop'));
            } else {
                return view('frontend.seller_shop_without_verification', compact('shop', 'seller'));
            }
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null && $type != null) {
            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function all_categories(Request $request)
    {
        //        $categories = Category::where('level', 0)->orderBy('name', 'asc')->get();
        $categories = Category::where('level', 0)->orderBy('order_level', 'desc')->get();
        return view('frontend.all_category', compact('categories'));
    }
    public function all_brands(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_brand', compact('categories'));
    }

    public function show_product_upload_form(Request $request)
    {
        $seller = Auth::user()->seller;
        if (addon_is_activated('seller_subscription')) {
            if ($seller->seller_package && $seller->seller_package->product_upload_limit > $seller->user->products()->count()) {
                $categories = Category::where('parent_id', 0)

                    ->with('childrenCategories')
                    ->get();
                return view('frontend.user.seller.product_upload', compact('categories'));
            } else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }
        $categories = Category::where('parent_id', 0)

            ->with('childrenCategories')
            ->get();
        return view('frontend.user.seller.product_upload', compact('categories'));
    }

    public function show_product_edit_form(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)

            ->with('childrenCategories')
            ->get();
        return view('frontend.user.seller.product_edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function seller_product_list(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(10);
        return view('frontend.user.seller.products', compact('products', 'search'));
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if (is_array($request->top_categories) && in_array($category->id, $request->top_categories)) {
                $category->top = 1;
                $category->save();
            } else {
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if (is_array($request->top_brands) && in_array($brand->id, $request->top_brands)) {
                $brand->top = 1;
                $brand->save();
            } else {
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $tax = 0;
        $max_limit = 0;

        if ($request->has('color')) {
            $str = $request['color'];
        }

        if (json_decode($product->choice_options) != null) {
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }

        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;

        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        $quantity = $product_stock->qty;
        $max_limit = $product_stock->qty;

        if ($quantity >= 1 && $product->min_qty <= $quantity) {
            $in_stock = 1;
        } else {
            $in_stock = 0;
        }

        //Product Stock Visibility
        if ($product->stock_visibility_state == 'text') {
            if ($quantity >= 1 && $product->min_qty < $quantity) {
                $quantity = translate('In Stock');
            } else {
                $quantity = translate('Out Of Stock');
            }
        }

        //discount calculation
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

        // taxes
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        $price += $tax;

        return array(
            'price' => single_price($price * $request->quantity),
            'quantity' => $quantity,
            'digital' => $product->digital,
            'variation' => $str,
            'max_limit' => $max_limit,
            'in_stock' => $in_stock
        );
    }

    public function sellerpolicy()
    {
        $page =  Page::where('type', 'seller_policy_page')->first();
        return view("frontend.policies.sellerpolicy", compact('page'));
    }

    public function returnpolicy()
    {
        $page =  Page::where('type', 'return_policy_page')->first();
        return view("frontend.policies.returnpolicy", compact('page'));
    }

    public function supportpolicy()
    {
        $page =  Page::where('type', 'support_policy_page')->first();
        return view("frontend.policies.supportpolicy", compact('page'));
    }

    public function terms()
    {
        $page =  Page::where('type', 'terms_conditions_page')->first();
        return view("frontend.policies.terms", compact('page'));
    }

    public function privacypolicy()
    {
        $page =  Page::where('type', 'privacy_policy_page')->first();
        return view("frontend.policies.privacypolicy", compact('page'));
    }

    public function get_pick_up_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request)
    {
        $category = Category::findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('category'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.user.customer_packages_lists', compact('customer_packages'));
    }

    public function seller_digital_product_list(Request $request)
    {
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.user.seller.digitalproducts.products', compact('products'));
    }
    public function show_digital_product_upload_form(Request $request)
    {
        $seller = Auth::user()->seller;
        if (addon_is_activated('seller_subscription')) {
            if ($seller->seller_package && $seller->seller_package->product_upload_limit > $seller->user->products()->count()) {
                $categories = Category::where('digital', 1)->get();
                return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
            } else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }
        $categories = Category::where('digital', 1)->get();
        return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
    }

    public function show_digital_product_edit_form(Request $request, $id)
    {
        $categories = Category::where('digital', 1)->get();
        $lang = $request->lang;
        $product = Product::find($id);
        return view('frontend.user.seller.digitalproducts.product_edit', compact('categories', 'product', 'lang'));
    }

    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = 'Email already exists!';
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if (isUnique($email)) {
            $this->send_email_change_verification_mail($request, $email);
            flash(translate('A verification mail has been sent to the mail you provided us with.'))->success();
            return back();
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = 'Email Verification';
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = 'Verify your account';
        $array['link'] = route('email_change.callback') . '?new_email_verificiation_code=' . $verification_code . '&email=' . $email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = "Email Second";

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");
        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request)
    {
        if ($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if ($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');
    }

    public function reset_password_with_code(Request $request)
    {
        if (($user = User::where('email', $request->email)->where('verification_code', $request->code)->first()) != null) {
            if ($request->password == $request->password_confirmation) {
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                flash(translate('Password updated successfully'))->success();

                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            } else {
                flash("Password and confirm password didn't match")->warning();
                return redirect()->route('password.request');
            }
        } else {
            flash("Verification code mismatch")->error();
            return redirect()->route('password.request');
        }
    }


    public function all_flash_deals()
    {
        $today = strtotime(date('Y-m-d H:i:s'));

        $data['all_flash_deals'] = FlashDeal::where('status', 1)
            ->where('start_date', "<=", $today)
            ->where('end_date', ">", $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view("frontend.flash_deal.all_flash_deal_list", $data);
    }

    public function all_seller(Request $request)
    {
        $shops = Shop::whereIn('user_id', verified_sellers_id())
            ->paginate(15);

        return view('frontend.shop_listing', compact('shops'));
    }

    public function all_coupons(Request $request)
    {
        $coupons = Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(15);
        return view('frontend.coupons', compact('coupons'));
    }

    public function inhouse_products(Request $request)
    {
        $products = filter_products(Product::where('added_by', 'admin'))->with('taxes')->paginate(12)->appends(request()->query());
        return view('frontend.inhouse_products', compact('products'));
    }

    public function productQuickView(Request $request)
    {
        $product = Product::with('brand')->find($request->id);
        return view('frontend.inc.product_quick_view_content', compact('product'));
    }

    public function productSameBrandView(Request $request)
    {
        $html = '';

        if ($request->brand_id) {
            $products = Product::where([
                'brand_id' => $request->brand_id,
                'published' => 1,
            ])
                ->where('id', '!=', $request->product_id)
                ->limit(3)->with('brand')->get();

            foreach ($products as $product) {
                $html .= view('frontend.inc.product_box', [
                    'product' => $product
                ]);
            }
        }

        return $html;
    }

    public function productRelatedProductsView(Request $request)
    {
        $html = '<div class="ps-carousel--nav owl-slider owl-slider2" data-owl-auto="true" data-owl-loop="true"
        data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true" data-owl-dots="true"
        data-owl-item="6" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3"
        data-owl-item-lg="4" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">';

        if ($request->product_id) {
            $products = Product::where([
                'published' => 1,
            ])
                ->where('id', '!=', $request->product_id)
                ->limit(3)->with('brand')->get();

            foreach ($products as $product) {
                $html .= view('frontend.inc.product_box', [
                    'product' => $product
                ]);
            }
        }
        $html .= '</div>';
        return $html;
    }

    public function productAlsoBoughtView(Request $request)
    {
        $html = '<div class="row">';

        if ($request->product_id) {
            $products = Product::where([
                'published' => 1,
            ])
                ->where('id', '!=', $request->product_id)
                ->limit(7)->with('brand')->get();

            foreach ($products as $product) {
                $html .= '<div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">';
                $html .= view('frontend.inc.product_box', [
                    'product' => $product
                ]);
                $html .= '</div>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}
