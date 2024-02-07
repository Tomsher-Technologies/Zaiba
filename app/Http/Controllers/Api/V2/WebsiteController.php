<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\SplashScreenCollection;
use App\Models\App\SplashScreens;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Product;
use App\Models\Offers;
use App\Utility\CategoryUtility;
use App\Models\Frontend\Banner;
use App\Models\Frontend\HomeSlider;
use App\Models\Subscriber;
use App\Models\HeaderMenus;
use App\Models\Stores;
use App\Models\Page;
use App\Models\Blog;
use App\Http\Resources\V2\WebHomeCategoryCollection;
use App\Http\Resources\V2\WebHomeBrandCollection;
use App\Http\Resources\V2\WebHomeOffersCollection;
use App\Http\Resources\V2\WebHomeProductsCollection;
use App\Models\Contacts;
use App\Mail\ContactEnquiry;
use Illuminate\Http\Request;
use Cache;
use Mail;
use Validator;
use DB;

class WebsiteController extends Controller
{
    public function websiteHeader()
    {
        $data = [];
        $data['menus'] =  Cache::remember('header_menus', 3600, function () {
            $menus = HeaderMenus::with(['category'])->orderBy('id', 'asc')->get();
            $data['menus'] = $menus;
            $details = [];
            if (!empty($menus[0])) {
                foreach ($menus as $mn) {
                    $details[] = [
                        'id' => $mn->category_id,
                        'name' => $mn['category']->name,
                        'slug' => $mn['category']->slug,
                        'sub_categories' => getImmediateSubCategories($mn->category_id),
                        'brands' => getHeaderCategoryBrands($mn->brands)
                    ];
                }
            }
            return $details;
        });
        $data['brands'] =  Cache::remember('header_brands', 3600, function () {
            $header_brands = get_setting('header_brands');
            $brands = Brand::whereIn('id', json_decode($header_brands))->get();
            return $brands;
        });
        return response()->json(['success' => true, "message" => "Success", "data" => $data], 200);
    }

    public function websiteHome()
    {
        $data['slider'] = Cache::rememberForever('homeSlider', function () {
            $slider = [];
            $sliders = HomeSlider::whereStatus(1)->with(['mainImage'])->orderBy('sort_order')->get();
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
                        'image' => api_upload_asset($slid->image)
                    ];
                }
                return $slider;
            }
        });

        $data['top_categories'] = Cache::rememberForever('top_categories', function () {
            $categories = get_setting('home_categories');
            if ($categories) {
                $details = Category::whereIn('id', json_decode($categories))
                    ->with(['icon'])
                    ->get();
                return  $details;
            }
        });

        $data['top_brands'] = Cache::rememberForever('top_brands', function () {
            $brands = get_setting('home_brands');
            if ($brands) {
                $details = Brand::whereIn('id', json_decode($brands))->get();
                return  $details;
            }
        });

        $data['best_selling'] = Cache::remember('best_selling_products', 3600, function () {
            $product_ids = get_setting('best_selling');
            if ($product_ids) {
                $products =  Product::where('published', 1)->whereIn('id', json_decode($product_ids))->with('brand')->get();
                return  $products;
            }
        });

        $data['offers'] = Cache::rememberForever('home_offers', function () {
            $offers = get_setting('home_offers');
            if ($offers) {
                $details = Offers::whereIn('id', json_decode($offers))->whereRaw('(now() between start_date and end_date)')->get();
                return $details;
            }
        });

        $home_banners = BusinessSetting::whereIn('type', array('home_banner_1', 'home_banner_2', 'home_banner_3'))->get()->keyBy('type');
        $banners = [];
        $all_banners = Banner::with(['mainImage'])->where('status', true)->get();
        foreach ($home_banners as $key => $hb) {
            $bannerid = json_decode($hb->value);
            if (!empty($bannerid)) {
                $bannerid = $bannerid[0];
            }
            $bannerData = $all_banners->where('id', $bannerid)->first();
            if (!empty($bannerData)) {
                $banners[$key] = array(
                    'type' => $bannerData->link_type ?? '',
                    'link' => $bannerData->link_type == 'external' ? $bannerData->link : $bannerData->getBannerLink(),
                    'type_id' => $bannerData->link_ref_id,
                    'image' => storage_asset($bannerData->mainImage->file_name)
                );
            } else {
                $banners[$key] = array();
            }
        }

        $data['banners'] = $banners;

        return response()->json(['success' => true, "message" => "Success", "data" => $data], 200);
    }

    public function footer()
    {
        return response()->json([
            'result' => true,
            'app_links' => array([
                'play_store' => array([
                    'link' => get_setting('play_store_link'),
                    'image' => api_asset(get_setting('play_store_image')),
                ]),
                'app_store' => array([
                    'link' => get_setting('app_store_link'),
                    'image' => api_asset(get_setting('app_store_image')),
                ]),
            ]),
            'social_links' => array([
                'facebook' => get_setting('facebook_link'),
                'twitter' => get_setting('twitter_link'),
                'instagram' => get_setting('instagram_link'),
                'youtube' => get_setting('youtube_link'),
                'linkedin' => get_setting('linkedin_link'),
            ]),
            'copyright_text' => get_setting('frontend_copyright_text'),
            'contact_phone' => get_setting('contact_phone'),
            'contact_email' => get_setting('contact_email'),
            'contact_address' => get_setting('contact_address'),
        ]);
    }

    public function offerDetails(Request $request)
    {
        $offerid = $request->offer_id;
        $limit = $request->has('limit') ? $request->limit : '';
        $offset = $request->has('offset') ? $request->offset : 0;
        if ($offerid != '') {
            $Offer = Offers::where('status', 1)->find($offerid);
            if (!$Offer) {
                return response()->json(['success' => false, "message" => "No Data Found!", "data" => []], 400);
            } else {
                $temp = array();
                $temp['id'] = $Offer->id;
                $temp['name'] = $Offer->name;
                $temp['type'] = $Offer->link_type;

                if ($Offer->link_type == 'product') {
                    $result = array();
                    $product_query  = Product::whereIn('id', json_decode($Offer->link_id))->wherePublished(1);
                    if ($limit != '') {
                        $product_query->skip($offset)->take($limit);
                    }
                    $products = $product_query->get();

                    foreach ($products as $prod) {
                        $tempProducts = array();
                        $tempProducts['id'] = $prod->id;
                        $tempProducts['name'] = $prod->name;
                        $tempProducts['image'] = app('url')->asset($prod->thumbnail_img);
                        $tempProducts['sku'] = $prod->sku;
                        $tempProducts['main_price'] = home_discounted_base_price_wo_currency($prod);
                        $tempProducts['min_qty'] = $prod->min_qty;
                        $tempProducts['slug'] = $prod->slug;

                        $result[] = $tempProducts;
                    }
                } elseif ($Offer->link_type == 'brand') {
                    $brandQuery =  Brand::with(['logoImage'])->whereIn('id', json_decode($Offer->link_id));
                    if ($limit != '') {
                        $brandQuery->skip($offset)->take($limit);
                    }
                    $brands = $brandQuery->get();
                    $result = array();
                    foreach ($brands as $brand) {
                        $tempBrands = array();
                        $tempBrands['id'] = $brand->id;
                        $tempBrands['name'] = $brand->name;
                        $tempBrands['image'] = storage_asset($brand->logoImage->file_name);
                        $result[] = $tempBrands;
                    }
                } elseif ($Offer->link_type == 'category') {
                    $categoriesQuery =  Category::whereIn('id', json_decode($Offer->link_id));
                    if ($limit != '') {
                        $categoriesQuery->skip($offset)->take($limit);
                    }
                    $categories = $categoriesQuery->get();
                    $result = array();
                    foreach ($categories as $category) {
                        $tempCats = array();
                        $tempCats['id'] = $category->id;
                        $tempCats['name'] = $category->name;
                        $tempCats['image'] = api_upload_asset($category->icon);
                        $result[] = $tempCats;
                    }
                }
                $temp['list'] = $result;
                $temp['next_offset'] = $offset + $limit;
                return response()->json(['success' => true, "message" => "Data fetched successfully!", "data" => $temp], 200);
            }
        } else {
            return response()->json(['success' => false, "message" => "No Data Found!", "data" => []], 400);
        }
    }

    public function homeAdBanners()
    {
        $all_banners = Banner::with(['mainImage'])->where('status', true)->get();

        $banner_id = BusinessSetting::whereIn('type', [
            'app_banner_1',
            'app_banner_2',
            'app_banner_3',
            'app_banner_4',
            'app_banner_5',
            'app_banner_6',
        ])->get();

        $banners = array();

        foreach ($banner_id as $banner) {
            $ids = json_decode($banner->value);
            if ($ids) {
                foreach ($ids as $id) {
                    $c_banner = $all_banners->where('id', $id)->first();
                    $banners[$banner->type][] = array(
                        // 'image1' => $c_banner,
                        'link_type' => $c_banner->link_type ?? '',
                        'link_id' => $c_banner->link_type == 'external' ? $c_banner->link : $c_banner->link_ref_id,
                        'image' => storage_asset($c_banner->mainImage->file_name)
                    );
                }
            }
        }

        return response()->json([
            "result" => true,
            "data" => $banners,
        ]);
    }

    public function websiteCategories($parent_id = 0)
    {
        $categoryList = Category::latest()->get();
        if ($categoryList) {
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $categoryList,
                'total_count' => Category::count()
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Category not found',
            ], 200);
        }
        // echo "Categories";die;
        // $categories =  Cache::remember('category_filter', 3600, function () {
        //     return $categories =  getSidebarCategoryTree();
        // });
        // return response()->json(['success' => true, "message" => "Success", "data" => $categories], 200);
    }
    public function websiteBrands()
    {
        $brandList = Brand::latest()->get();
        if ($brandList) {
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $brandList,
                'total_count' => Brand::count()
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Category not found',
            ], 200);
        }
        // echo "Categories";die;
        // $categories =  Cache::remember('category_filter', 3600, function () {
        //     return $categories =  getSidebarCategoryTree();
        // });
        // return response()->json(['success' => true, "message" => "Success", "data" => $categories], 200);
    }
    public function categoryOffers()
    {
        $offers = Offers::with(['category'])->where('link_type', 'category')->where('status', 1)->whereRaw('(now() between start_date and end_date)')->get();

        $result = [];
        if ($offers) {
            foreach ($offers as $off) {
                $brandIds = json_decode($off->link_id);
                $brands = Brand::whereIn('id', $brandIds)->get();

                $result[$off->category->name]['offer'] = [
                    'id' => $off->id,
                    'name' => $off->name,
                    'slug' => $off->slug,
                    'category_name' => $off->category->name
                ];
                if ($brands) {
                    foreach ($brands as $brds) {
                        $result[$off->category->name]['brands'][] = [
                            'id' => $brds->id,
                            'name' => $brds->name,
                            'slug' => $brds->slug,
                            'logo' => api_upload_asset($brds->logo),
                            'offer_tag' => getOfferTag($off)
                        ];
                    }
                }
            }
        }
        return response()->json(['success' => true, "message" => "Success", "data" => $result], 200);
    }

    public function contactUs(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'message' => 'required'
        ]);
        if($validator->fails()){
            if($request->name == '' || $request->email == '' || $request->phone == '' || $request->message == ''){
                return response()->json(['status' => false, 'message' => 'Please make sure that you fill out all the required fields..', 'data' => []  ], 200);
            }else{
                $errors = $validator->errors();
                
                if ($errors->has('email')) {
                    return response()->json(['status' => false, 'message' => $errors->first('email'), 'data' => []  ], 200);
                }
            
                if ($errors->has('phone')) {
                    return response()->json(['status' => false, 'message' => $errors->first('phone'), 'data' => []  ], 200);
                }
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => []  ], 200);
            }
        }

        $con                = new Contacts;
        $con->name          = $request->name;
        $con->email         = $request->email;
        $con->phone         = $request->phone;
        $con->subject       = $request->subject ?? NULL;
        $con->message       = $request->message;
        $con->save();

        Mail::to(env('MAIL_ADMIN'))->queue(new ContactEnquiry($con));

        return response()->json(['status' => true,"message"=>"Thank you for getting in touch. Our team will contact you shortly.","data" => []],200);
    }

    public function storeLocations(){
        $shops = Stores::where('status',1)->orderBy('name','asc')->get();

        $meta = Page::where('type', 'store_locator')->select('heading1 as title','title as page_heading', 'meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image')->first();
        
        if($meta){
            $meta->meta_image   = ($meta->meta_image != NULL) ? uploaded_asset($meta->meta_image) : '';
        }
        return response()->json(['status' => true,"message"=>"Success","data" => $shops,"page_data" => $meta],200);
    }
    // DB::raw('CONCAT('.url()."', image) as images))

    public function blogs(Request $request){
        $limit = $request->limit ? $request->limit : 9;
        $offset = $request->offset ? $request->offset : 0;

        $blogsQuery = Blog::where('status',1)->orderBy('blog_date','desc')->select('id','title', 'slug', 'description', 'blog_date', 'status', 'seo_title', 'og_title', 'twitter_title', 'seo_description', 'og_description', 'twitter_description', 'keywords',DB::raw("CONCAT('".url('/')."', image) AS image"));
        
        $total_count = $blogsQuery->count();
        $blogs = $blogsQuery->skip($offset)->take($limit)->get();
        $next_offset = $offset + $limit;

        $meta = Page::where('type', 'blog_list')->select('meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image')->first();
        
        if($meta){
            $meta->meta_image   = ($meta->meta_image != NULL) ? uploaded_asset($meta->meta_image) : '';
        }
        return response()->json(['status' => true,"message"=>"Success","data" => $blogs, "total_count" => $total_count, "next_offset" => $next_offset,"page_data" => $meta],200);
    }

    public function blogDetails(Request $request){
        $slug = $request->has('slug') ? $request->slug : null;
        if($slug != null){
            $blogsQuery = Blog::where('slug', $slug)
                                ->where('status',1)
                                ->select('id','title', 'slug', 'description', 'blog_date', 'status', 'seo_title', 'og_title', 'twitter_title', 'seo_description', 'og_description', 'twitter_description', 'keywords',DB::raw("CONCAT('".url('/')."', image) AS image"))
                                ->orderBy('blog_date','desc')
                                ->first();
            return response()->json(['success' => true,"message"=>"Success","data" => $blogsQuery],200);
        }else{
            return response()->json(['success' => false,"message"=>"No data","data" => []],200);
        }
    }
}
