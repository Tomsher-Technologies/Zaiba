<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EmailVerificationNotification;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Frontend\HomeSlider;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MenuItems;
use App\Models\Menu;
use App\Models\Category;
use App\Models\BusinessSetting;
use App\Models\Frontend\Banner;
use App\Models\Page;
use App\Models\Product;
use Validator;
use Hash;
use Str;
use File;
use Storage;
use DB;
use Cache;

class ApiAuthController extends Controller
{
  
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|unique:users,phone',
        ]);
        if($validator->fails()){
            if($request->name == '' || $request->email == '' || $request->password == '' || $request->phone_number == ''){
                return response()->json(['status' => false, 'message' => 'Please make sure that you fill out all the required fields..', 'data' => []  ], 400);
            }else{
                $errors = $validator->errors();
                if ($errors->has('email')) {
                    return response()->json(['status' => false, 'message' => $errors->first('email'), 'data' => []  ], 400);
                }
                if ($errors->has('phone_number')) {
                    return response()->json(['status' => false, 'message' => $errors->first('phone_number'), 'data' => []  ], 400);
                }
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => []  ], 400);
            }
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone_number,
            'password' => Hash::make($request->password),
            'verification_code' => rand(100000, 999999)
        ]);
        $user->save();

        $details = [
            'name' => $request->name,
            'subject' => 'Welcome to '.env('APP_NAME').' - Your Sparkling Journey Begins Here!',
            'body' => " <p> We are thrilled to welcome you to ".env('APP_NAME').", where elegance, style, and beauty come together to adorn your world. Thank you for choosing us as your go-to destination for all things jewelry.</p><br>
            <p>To start exploring, simply log in to your account using the credentials you provided during registration. If you have any questions or need assistance, please don't hesitate to reach out to our customer support team.</p><br>
            <p>We look forward to serving you and being a part of your jewelry story. Happy shopping, and here's to a world adorned with your unique elegance!</p>"
        ];
       
        \Mail::to($request->email)->send(new \App\Mail\SendMail($details));

        $otp = generateOTP($user);

        $data['message'] = generateOTPMessage($user->name, $otp['otp']); 
        $data['phone'] = $user->phone_number;
        
        $sendStatus = sendOTP($data);

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();

        return response()->json([
            'status' => true,
            'message' => translate('Registration Successful. Please verify your Mobile number.'),
            'data' => $user->id
        ], 200);
    }

    public function login(Request $request){
        $email      = $request->email;
        $password   = $request->password;

        $user = User::whereIn('user_type', ['customer'])->where('email', $email)->first();
        if ($user != null) {
            if (Hash::check($password, $user->password)) {
                return $this->loginSuccess($user);
            } else {
                return response()->json(['status' => false, 'message' => translate('Unauthorized'),'data' => []], 401);
            }
        } else {
            return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 401);
        }
    }

    protected function loginSuccess($user)
    {
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'status' => true,
            'message' => translate('Successfully logged in'),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => null,
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_original' => api_asset($user->avatar_original),
                'phone' => $user->phone
            ]
        ]);
    }

    public function loginWithOTP(Request $request){
        $phone = $request->phone;

        $user = User::whereIn('user_type', ['customer'])->where('phone', $phone)->first();
        if ($user != null) {
            $otp = generateOTP($user);

            $data['message'] = generateOTPMessage($user->name, $otp['otp']); 
            $data['phone'] = $phone;

            $sendStatus = sendOTP($data);
            return response()->json([
                                'status' => true,
                                'message' => translate('An OTP has been sent to the provided mobile number. Please check your messages.'),
                                'data' => [
                                    'sent' => $sendStatus ? true : false,
                                    'user_id' => $user->id,
                                    'expiry' => date('Y-m-d H:i:s',strtotime($otp['otp_expiry']))
                                ]
                            ], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 401);
        }
    }

    public function verifyOTP(Request $request){
        $user_id = $request->user_id;
        $otp = $request->otp;

        // || !verifyOTP($user,$otp)
        if ($user_id == '' || $otp == '') {
            return response()->json(['status'=>false,'message'=>'Invalid details.','data' => []]);
        }else{
            $user = User::find($user_id);
            if($user){
                $verify = verifyUserOTP($user, $otp);
                if($verify){
                    return $this->loginSuccess($user);
                }else{
                    return response()->json(['status' => false, 'message' => translate('Invalid or expired OTP.'), 'data' => null], 401);
                }
            }else{
                return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 401);
            }
        }
    }

    public function resendOTP(Request $request){
        $user_id = $request->user_id;

        $user = User::find($user_id);
        if ($user != null) {
            $otp = generateOTP($user);

            $data['message'] = generateOTPMessage($user->name, $otp['otp']); 
            $data['phone'] = $user->phone;

            $sendStatus = sendOTP($data);
            return response()->json([
                                'status' => true,
                                'message' => translate('An OTP has been resend sent to the provided mobile number. Please check your messages.'),
                                'data' => [
                                    'sent' => $sendStatus ? true : false,
                                    'user_id' => $user->id,
                                    'expiry' => date('Y-m-d H:i:s',strtotime($otp['otp_expiry']))
                                ]
                            ], 200);
        } else {
            return response()->json(['status' => false, 'message' => translate('User not found'), 'data' => []], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => translate('Successfully logged out'),
            'data' => []
        ],200);
    }

    public function user(Request $request)
    {
        $user = User::with(['addresses'])->find($request->user());
                    
        if(isset($user[0])){
            $data['id'] = $user[0]['id'] ?? '';
            $data['name'] = $user[0]['name'] ?? '';
            $data['email'] = $user[0]['email'] ?? '';
            $data['phone'] = $user[0]['phone'] ?? '';
            $data['phone_verified'] = $user[0]['is_phone_verified'] ?? '';
            $dataAddress = $user[0]['addresses'] ?? [];
            $address = [];
            if($dataAddress){
                foreach($dataAddress as $adds){
                    $address[] = [
                        'id'=>$adds['id'],
                        'name'=>$adds['name'],
                        'address'=>$adds['address'],
                        'country_id'=>$adds['country_id'],
                        'country_name'=>$adds['country']['name'],
                        'state_id'=>$adds['state_id'],
                        'state_name'=>$adds['state']['name'],
                        'city_id'=>$adds['city_id'],
                        'city_name'=>$adds['city']['name'],
                        'postal_code'=>$adds['postal_code'],
                        'latitude'=>$adds['latitude'],
                        'longitude'=>$adds['longitude'],
                        'phone'=>$adds['phone'],
                        'is_default'=>$adds['set_default']
                    ];
                }
            }

            $data['address'] = $address;
            return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $data]);
        }else{
            return response()->json([ 'status' => false, 'message' => 'User details not found.', 'data' => []]);
        }                                                           
    }

    public function updateProfile(Request $request){
        $id = $request->user()->id;
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email,'.$id,
            'phone_number' => 'nullable|unique:users,phone,'.$id,
        ]);
        
        if($validator->fails()){
            $errors = $validator->errors();
            if ($errors->has('email')) {
                return response()->json(['status' => false, 'message' => $errors->first('email'), 'data' => []  ], 400);
            }
            if ($errors->has('phone_number')) {
                return response()->json(['status' => false, 'message' => $errors->first('phone_number'), 'data' => []  ], 400);
            }
        }
        
        $name   = $request->name;
        $email  = $request->email;
        $phone  = $request->phone_number;
       
        $user = User::find($id);

        $old_phone = $user->phone;
        if($old_phone != $phone){
            $user->is_phone_verified = 0;
        }
        $user->phone = $phone;
        $user->name = $name;
        $user->email = $email;
        $user->save();
        return response()->json(['status' => true,'message' => 'User details updated successfully', 'data' => []],200);
    }

    public function changePassword(Request $request)
    {
        $userId = $request->user()->id;
        $user = User::find($userId);
        if (!Hash::check($request->current_password, $user->password)){
            return response()->json(['status' => false,'message' => 'Old password is incorrect', 'data' => []]);
        }
 
        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0){
            return response()->json(['status' => false,'message' => 'New Password cannot be same as your current password.', 'data' => []]);
        }

        $user->password =  Hash::make($request->new_password);
        $user->save();
        return response()->json(['status' => true,'message' => 'Password Changed Successfully', 'data' => []]);
    }

    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'postal_code' => 'required',
            'phone' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => 'Please make sure that you fill out all the required fields..', 'data' => []  ], 400);
        }

        $userId = $request->user()->id;
        $user = User::find($userId);
        
        if($user){
            $address                = new Address;
            $address->user_id       = $userId;
            $address->name          = $request->name;
            $address->address       = $request->address;
            $address->country_id    = $request->country_id;
            $address->state_id      = $request->state_id;
            $address->city_id       = $request->city_id;
            $address->longitude     = $request->longitude;
            $address->latitude      = $request->latitude;
            $address->postal_code   = $request->postal_code;
            $address->phone         = $request->phone;
            $address->save();
            return response()->json(['status' => true,'message' => 'Address added Successfully', 'data' => []]);
        }else {
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 401);
        }
    }

    public function updateAddress(Request $request)
    {
        $userId = $request->user()->id;
        $user = User::find($userId);
        $id = $request->address_id;
        if($user){
            $address = Address::findOrFail($id);
            $address->name          = $request->name;
            $address->address       = $request->address;
            $address->country_id    = $request->country_id;
            $address->state_id      = $request->state_id;
            $address->city_id       = $request->city_id;
            $address->longitude     = $request->longitude;
            $address->latitude      = $request->latitude;
            $address->postal_code   = $request->postal_code;
            $address->phone         = $request->phone;
            $address->save();
            return response()->json(['status' => true,'message' => 'Address updated Successfully', 'data' => []]);
        }else {
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 401);
        }
    }

    public function setDefaultAddress(Request $request){
        $userId = $request->user()->id;
        $user = User::find($userId);
        $id = $request->address_id;
        if($user){
            // Update all addresses to non-default first.
            Address::where('user_id',$userId)->update(['set_default'=>0]);
            // Make the selected address default.
            $address = Address::findOrFail($id);
            $address->set_default = 1;
            $address->save();
            return response()->json(['status' => true,'message' => 'Default address set Successfully', 'data' => []]);
        }else{
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 401);
        }
    }

    public function deleteAddress(Request $request){
        $userId = $request->user()->id;
        $user = User::find($userId);
        $id = $request->address_id;
        if($user){
            $address = Address::findOrFail($id);
            $address->is_deleted = 1;
            $address->save();
            return response()->json(['status' => true,'message' => 'Address deleted successfully', 'data' => []]);
        }else{
            return response()->json(['status' => false, 'message' => 'User not found', 'data' => []], 401);
        }
    }

    public function homePage(){
        // echo '<pre>';
        $page         = Page::where('type','home_page')->first();
        
        $data['slider'] = Cache::rememberForever('homeSlider', function () {
            $slider = [];
            $sliders = HomeSlider::whereStatus(1)->with(['mainImage', 'mobileImage'])->orderBy('sort_order')->get();
            if ($sliders) {
                foreach ($sliders as $slid) {
                    $slider[] = [
                        'id' => $slid->id,
                        'name' => $slid->name,
                        'type' => $slid->link_type,
                        'link' => $slid->getBannerLink(),
                        'type_id' => $slid->link_ref_id,
                        'sort_order' => $slid->sort_order,
                        'status' => $slid->status,
                        'image' => api_upload_asset($slid->image),
                        'mob_image' => api_upload_asset($slid->mobile_image)
                    ];
                }
                return $slider;
            }
        });

        $collections['title'] =  $page->heading1;
        $collections['sub_title'] = $page->sub_heading1;
        $newCollection = (get_setting('new_collection_categories') != null && get_setting('new_collection_categories') != 'null') ? json_decode(get_setting('new_collection_categories')) : [];
        $collections['categories'] = Category::whereIn('id',$newCollection)
                                        ->select('id','parent_id','name','slug')
                                        ->where('is_active',1)
                                        ->with(['products'=>function($query){
                                                $query->select('id', 'name', 'category_id', 'sku', 'unit_price', 'slug',\DB::raw('CONCAT("'.url('/').'", thumbnail_img) as thumbnail_img'))->where('published',1)
                                                ->take(8)
                                                ->latest();
                                            },])
                                        ->get();
        if($collections['categories']){
            foreach($collections['categories'] as $coll_cat){
                $categoryProducts = [];
                if($coll_cat->products){
                    foreach($coll_cat->products as $col_prod){
                        $stock = $col_prod->stocks()->orderBy('metal_weight','asc')->first();
                        $priceData = getProductPrice($stock);
                        $categoryProducts[] = [
                            'id' => $col_prod->id,
                            'name' => $col_prod->name,
                            'sku' => $col_prod->sku,
                            'thumbnail_image' => app('url')->asset($col_prod->thumbnail_img),
                            'stroked_price' => $priceData['original_price'],
                            'main_price' => $priceData['discounted_price'],
                            'min_qty' => $col_prod->min_qty,
                            'slug' => $col_prod->slug,
                            'offer_tag' => $priceData['offer_tag']
                        ];
                    }
                }
                unset($coll_cat->products);
                $coll_cat->products = $categoryProducts;
            }
        }

        $data['new_collection'] = $collections;
        
        $current_banners = BusinessSetting::whereIn('type', array('home_banner', 'home_mid_banner', 'home_large_banner'))->get()->keyBy('type');
        $data['collection_banners'] = Cache::rememberForever('newCollectionBanners', function () use($current_banners) {
            $colBanners = [];
            $collection_banners = (isset($current_banners['home_banner'])) ? json_decode($current_banners['home_banner']->value) : [];
            
            if(!empty($collection_banners)){
                $colBanners =  Banner::whereIn('id',$collection_banners)->where('status',1)
                                        ->select('id', 'name', 'image', 'mobile_image', 'title', 'sub_title', 'btn_text', 'link_type','link_ref', 'link_ref_id', 'link', 'status')
                                        ->get();
                if($colBanners){
                    foreach($colBanners as $colB){
                        $colB->image = api_upload_asset($colB->image);
                        $colB->mobile_image = api_upload_asset($colB->mobile_image);
                        $colB->link = $colB->getBannerLink();
                        unset($colB->link_ref);
                        unset($colB->link_ref_id);
                    }
                }
            }
            return $colBanners;
        });
       
        $data['trending_categories'] = Cache::rememberForever('home_trending_categories', function () use($page){
            $home_categories['title'] =  $page->heading2;
            $home_categories['sub_title'] = $page->sub_heading2;
            $catIds = (get_setting('home_categories') != null && get_setting('home_categories') != 'null') ? json_decode(get_setting('home_categories')) : [];
            if(!empty($catIds)){
                $home_categories['categories'] = Category::with(['icon'=>function($query){
                                                        $query->select('id', \DB::raw('CONCAT("'.url('/storage').'/", file_name) as file_name'));
                                                    },])->whereIn('id',$catIds)
                                                    ->select('id','parent_id','name','icon','slug')
                                                    ->where('is_active',1)
                                                    ->get();
            }
            return $home_categories;
        });

        $home_products['title'] =  $page->heading3;
        $home_products['sub_title'] = $page->sub_heading3;
        $home_products['products'] = [];
        $proIds = (get_setting('trending_products') != null && get_setting('trending_products') != 'null') ? json_decode(get_setting('trending_products')) : [];
        if(!empty($proIds)){
            $homeProducts = Product::whereIn('id',$proIds)
                                    ->select('id', 'name', 'slug','sku', 'unit_price', \DB::raw('CONCAT("'.url('/').'", thumbnail_img) as thumbnail_img'))
                                    ->where('published',1)
                                    ->get();
            if($homeProducts){
                foreach($homeProducts as $hmProd){
                    $stock = $hmProd->stocks()->orderBy('metal_weight','asc')->first();
                    $priceData = getProductPrice($stock);
                    $home_products['products'][] = [
                        'id' => $hmProd->id,
                        'name' => $hmProd->name,
                        'sku' => $hmProd->sku,
                        'thumbnail_image' => app('url')->asset($hmProd->thumbnail_img),
                        'stroked_price' => $priceData['original_price'],
                        'main_price' => $priceData['discounted_price'],
                        'min_qty' => $hmProd->min_qty,
                        'slug' => $hmProd->slug,
                        'offer_tag' => $priceData['offer_tag']
                    ];
                }
            }
        }

        $data['trending_products'] = $home_products;

        $data['highlights'] = Cache::rememberForever('home_highlights', function () use($page){
            $highlights['title'] = $page->heading4;
            $highlights['sub_title'] = $page->sub_heading4;
            $highlights['counts'] = [
                'count_1_icon' => api_upload_asset($page->image1),
                'count_1_count' => $page->heading5,
                'count_1_title' => $page->sub_heading5,
                'count_2_icon' => api_upload_asset($page->image2),
                'count_2_count' => $page->heading6,
                'count_2_title' => $page->sub_heading6
            ];
            $highlights['points'] = [
                'point_1_icon' => api_upload_asset($page->image3),
                'point_1_title' => $page->title1,
                'point_2_icon' => api_upload_asset($page->image4),
                'point_2_title' => $page->title2,
                'point_3_icon' => api_upload_asset($page->image5),
                'point_3_title' => $page->title3,
                'point_4_icon' => api_upload_asset($page->image6),
                'point_4_title' => $page->title4,
                'point_5_icon' => api_upload_asset($page->image7),
                'point_5_title' => $page->title5,
                'point_6_icon' => api_upload_asset($page->image8),
                'point_6_title' => $page->title6,
            ];
            return $highlights;
        });

        $data['mid_banners'] = Cache::rememberForever('home_mid_banners', function ()  use($current_banners){
            $mid_banners = [];
            $midBanners = (isset($current_banners['home_mid_banner'])) ? json_decode($current_banners['home_mid_banner']->value) : [];
            
            if(!empty($midBanners)){
                $mid_banners =  Banner::whereIn('id',$midBanners)->where('status',1)
                                        ->select('id', 'name', 'image', 'mobile_image', 'title', 'sub_title', 'btn_text', 'link_type','link_ref', 'link_ref_id', 'link', 'status')
                                        ->get();
                if($mid_banners){
                    foreach($mid_banners as $colM){
                        $colM->image = api_upload_asset($colM->image);
                        $colM->mobile_image = api_upload_asset($colM->mobile_image);
                        $colM->link = $colM->getBannerLink();
                        unset($colM->link_ref);
                        unset($colM->link_ref_id);
                    }
                }
            }
            return $mid_banners;
        });

        $data['about_us'] = Cache::rememberForever('home_about_us', function () use($page){
            $about_us['title'] = $page->heading7;
            $about_us['sub_title'] = $page->sub_heading7;
            $about_us['description'] = $page->description;
            $about_us['image1'] = api_upload_asset($page->image9);
            $about_us['image2'] = api_upload_asset($page->image10);
            return $about_us;
        });

        $data['newsletter'] = Cache::rememberForever('home_newsletter', function () use($page){
            $newsletter['title'] = $page->heading8;
            $newsletter['sub_title'] = $page->sub_heading8;
            $newsletter['description'] = $page->content8;
            $newsletter['image'] = api_upload_asset($page->image11);
            return $newsletter;
        });

        $data['get_inspired'] = Cache::rememberForever('home_get_inspired', function () use($page){
            $get_inspired['title'] = $page->heading9;
            $get_inspired['sub_title'] = $page->sub_heading9;
            $get_inspired['image1'] = api_upload_asset($page->image12);
            $get_inspired['image2'] = api_upload_asset($page->image13);
            $get_inspired['image3'] = api_upload_asset($page->image14);
            $get_inspired['image4'] = api_upload_asset($page->image15);
            return $get_inspired;
        });

        $data['footer_points'] = Cache::rememberForever('home_footer_points', function () use($page){
            $footer_points = [];
            for ($i=0; $i<4; $i++){
                $points = (get_setting('home_footer_point_'.$i+1) != null && get_setting('home_footer_point_'.$i+1) != 'null') ?  json_decode(get_setting('home_footer_point_'.$i+1), true) : [];
                $footer_points[$i] = [
                    'title' => $points['title'] ?? '',
                    'sub_title' => $points['sub_title'] ?? '',
                ];
            }
            return $footer_points;
        });

        $data['meta_data'] = Cache::rememberForever('home_meta_data', function () use($page){
            $meta_data['meta_title'] = $page->meta_title;
            $meta_data['meta_description'] = $page->meta_description;
            $meta_data['og_title'] = $page->og_title;
            $meta_data['og_description'] = $page->og_description;
            $meta_data['twitter_title'] = $page->twitter_title;
            $meta_data['twitter_description'] = $page->twitter_description;
            $meta_data['keywords'] = $page->keywords;
            $meta_data['meta_image'] = api_upload_asset($page->meta_image);
            return $meta_data;
        });

        return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $data],200);
    }

    public function getMenus(){

        $menus = Menu::with('items')->get();

        foreach($menus as $key => $main){
            foreach($main->items as $keyS => $sub){
                $main->items[$keyS]['img_1'] = (isset($sub->image1->file_name)) ? storage_asset($sub->image1->file_name) : '';
                $main->items[$keyS]['img_2'] = (isset($sub->image2->file_name)) ? storage_asset($sub->image2->file_name) : '';
                $main->items[$keyS]['img_3'] = (isset($sub->image3->file_name)) ? storage_asset($sub->image3->file_name) : '';

                unset($sub->image1);
                unset($sub->image2);
                unset($sub->image3);
            }
        }

        return response()->json([ 'status' => true, 'message' => 'Success', 'data' => $menus],200);
    }
}
