<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\ProductCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Http\Resources\V2\ProductDetailCollection;
use App\Http\Resources\V2\FlashDealCollection;
use App\Http\Resources\V2\ProductFilterCollection;
use App\Models\FlashDeal;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\Shop;
use App\Models\Color;
use App\Models\Page;
use App\Models\Review;
use App\Models\AttributeValue;
use App\Models\ProductAttributes;
use Illuminate\Http\Request;
use App\Utility\CategoryUtility;
use App\Utility\SearchUtility;
use Cache;
use DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        
        $limit = $request->limit ? $request->limit : 10;
        $offset = $request->offset ? $request->offset : 0;
        $min_price = $request->min_price ? $request->min_price : '';
        $max_price = $request->max_price ? $request->max_price : '';

        $category_slug = $request->category_slug ? explode(',', $request->category_slug)  : false;
        $brand_slug = $request->brand_slug ? explode(',', $request->brand_slug)  : false;

        // $product_query  = Product::wherePublished(1);
        // DB::enableQueryLog();
        $product_query = ProductStock::leftJoin('products','products.id','=','product_stocks.product_id')
                                    ->where('products.published',1)
                                    ->where('product_stocks.status',1)
                                    ->select('product_stocks.*');
        // dd(DB::getQueryLog());
        // echo '<pre>';
        // print_r($product_query);
        // die;

        $metaData = Page::where('type', 'product_listing')->select('image1','meta_title', 'meta_description', 'keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_image')->first();
        if($metaData){
            $metaData->image1           = ($metaData->image1 != NULL) ? uploaded_asset($metaData->image1) : '';
            $metaData->meta_image       = ($metaData->meta_image != NULL) ? uploaded_asset($metaData->meta_image) : '';
            $metaData->footer_title     = '';
            $metaData->footer_content   = '';
        }
        
        $meta = NULL;
       
        if ($category_slug) {
            $catids = implode(',', $category_slug);
            $childIds = [];
            $category_ids = Category::whereIn('slug',$category_slug)->pluck('id')->toArray();
            $childIds[] = $category_ids;
            if(!empty($category_ids)){
                foreach($category_ids as $cId){
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            
            $product_query->whereIn('products.category_id', $childIds);
            if(!empty($category_ids)){
                $meta = Category::select('meta_title', 'meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_keyword', 'footer_title', 'footer_content')->find($category_ids[0]);
            }
        }   

        if ($brand_slug) {
            $brand_ids = Brand::whereIn('slug',$brand_slug)->pluck('id')->toArray();
            $product_query->whereIn('products.brand_id', $brand_ids);
        }

        if ($request->order_by) {
            switch ($request->order_by) {
                case 'latest':
                    $product_query->latest();
                    break;
                case 'oldest':
                    $product_query->oldest();
                    break;
                case 'name_asc':
                    $product_query->orderBy('products.name', 'asc');
                    break;
                case 'name_desc':
                    $product_query->orderBy('products.name', 'desc');
                    break;
                case 'price_high':
                    // $product_query->select('*', DB::raw("(SELECT MAX(price) from product_stocks WHERE product_id = products.id) as sort_price"));
                    // $product_query->orderBy('sort_price', 'desc');
                    $product_query->orderBy('product_stocks.offer_price','desc');
                    break;
                case 'price_low':
                    // $product_query->select('*', DB::raw("(SELECT MIN(price) from product_stocks WHERE product_id = products.id) as sort_price"));
                    // $product_query->orderBy('sort_price', 'asc');
                    $product_query->orderBy('product_stocks.offer_price','asc');
                    break;
                default:
                    # code...
                    break;
            }
        }

        if ($request->search) {
            $sort_search = $request->search;
            $products = $product_query->where('products.name', 'like', '%' . $sort_search . '%')
                        ->orWhere('products.tags', 'like', '%' . $sort_search . '%')
                        ->orWhere('product_stocks.sku', 'like', '%' . $sort_search . '%');
            SearchUtility::store($sort_search, $request);
        }
        if ($min_price != null && $min_price != "" && is_numeric($min_price)) {
            $product_query->where('product_stocks.offer_price', '>=', $min_price);
        }

        if ($max_price != null && $max_price != "" && is_numeric($max_price)) {
            $product_query->where('product_stocks.offer_price', '<=', $max_price);
        }

        $total_count = $product_query->count();
        $products = $product_query->skip($offset)->take($limit)->get();
        // dd(DB::getQueryLog());
        $next_offset = $offset + $limit;
      
        $response = new ProductFilterCollection($products);
        
        if($meta != NULL){
            $metaData->meta_title           = ($meta->meta_title != '') ? $meta->meta_title : $metaData->meta_title ;
            $metaData->meta_description     = ($meta->meta_description != '') ? $meta->meta_description : $metaData->meta_description ;
            $metaData->og_title             = ($meta->og_title != '') ? $meta->og_title : $metaData->og_title ;
            $metaData->og_description       = ($meta->og_description != '') ? $meta->og_description : $metaData->og_description ;
            $metaData->twitter_title        = ($meta->twitter_title != '') ? $meta->twitter_title : $metaData->twitter_title ;
            $metaData->twitter_description  = ($meta->twitter_description != '') ? $meta->twitter_description : $metaData->twitter_description ;
            $metaData->keywords             = ($meta->meta_keyword != '') ? $meta->meta_keyword : $metaData->keywords ;
            $metaData->footer_title         = $meta->footer_title;
            $metaData->footer_content       = $meta->footer_content;
            
        }
  
        return response()->json(['success' => true,"message"=>"Success","data" => $response, "total_count" => $total_count, "next_offset" => $next_offset, 'meta' => $metaData ],200);
    }

    public function relatedProducts(Request $request){
        $limit = $request->limit ? $request->limit : 10;
        $offset = $request->offset ? $request->offset : 0;
        $category_slug = $request->category_slug ?? '';
        $product_slug = $request->product_slug ?? '';

       
        $product_query = ProductStock::leftJoin('products','products.id','=','product_stocks.product_id')
                                    ->where('products.published',1)
                                    ->where('product_stocks.status',1)
                                    ->select('product_stocks.*');

        if ($category_slug) {
            $category_ids = Category::where('slug',$category_slug)->pluck('id')->toArray();
            $childIds[] = $category_ids;
            if(!empty($category_ids)){
                foreach($category_ids as $cId){
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }

            $product_query->whereIn('products.category_id', $category_ids);
        }
        $product_query->where('products.slug','!=', $product_slug)->latest();

        $products = $product_query->skip($offset)->take($limit)->get();
        
        $response = new ProductFilterCollection($products);
      
        return response()->json(['success' => true,"message"=>"Success","data" => $response],200);
    }

    public function productDetails(Request $request){
        $slug = $request->has('slug') ? $request->slug :  ''; 
        $sku  = $request->has('sku') ? $request->sku :  ''; 

        if($slug !=  '' && $sku !=''){
            $product_stock = ProductStock::leftJoin('products','products.id','=','product_stocks.product_id')
                                    ->where('products.published',1)
                                    ->where('product_stocks.status',1)
                                    ->select('product_stocks.*')
                                    ->where('products.slug', $slug)
                                    ->where('product_stocks.sku', $sku)
                                    ->first();

            // print_r($varient_products);
            // die;
            $category = [
                'id'=> 0,
                'name'=> "",
                'slug' => "",
                'logo'=> "",
            ];
            $response = [];
            if($product_stock){

                $currentAttributes = ($product_stock->product->product_type == 1) ? $product_stock->attributes->toArray() : [];

                $curAttr = [];
                if($currentAttributes){
                    foreach ($currentAttributes as $cAttr) {
                        $curAttr[$cAttr['attribute_id']] = $cAttr['attribute_value_id'];
                    }
                }

                $productAttributes = ($product_stock->product->product_type == 1) ? json_decode($product_stock->product->attributes) : [];
                $prodAttr = [];
            
                if($productAttributes){
                    $allAttributes = Attribute::pluck('name','id')->toArray();
                    $allAttributeValues = AttributeValue::pluck('value','id')->toArray();
                    foreach($productAttributes as $pAttr){
                        $attrs = [];
                        $attrs['id'] = $pAttr;
                        $attrs['name'] = $allAttributes[$pAttr];
                        $ids = ProductAttributes::where('product_id', $product_stock->product_id)->where('attribute_id',$pAttr)->pluck('attribute_value_id')->toArray();
                        $ids = array_unique($ids);
                        $values = [];
                        foreach($ids as  $vId){
                            $attrVal['id'] = $vId;
                            $attrVal['name'] = $allAttributeValues[$vId];
                            $values[] = $attrVal;
                        }
                        $attrs['values'] = $values;
                        $prodAttr[] = $attrs;
                    }
                }
                $varient_products = [];

                $varientProducts = ProductAttributes::leftJoin('product_stocks as ps','ps.id','=','product_attributes.product_varient_id')
                                                ->where('product_attributes.product_id', $product_stock->product_id)
                                                ->groupBy('product_attributes.product_varient_id')
                                                ->select(DB::raw('product_attributes.product_varient_id,ps.sku,
                                                GROUP_CONCAT(product_attributes.attribute_value_id) AS attr_values'))
                                                ->get();
                // print_r($varientProducts);
                if($varientProducts){
                    foreach($varientProducts as $varProd){
                        $varient_products[] = [
                            $varProd->sku => explode(',',$varProd->attr_values)
                        ];
                    }
                }

                if($product_stock->product->category != null) {
                    $category = [
                        'id'=> $product_stock->product->category->id ?? '',
                        'name'=> $product_stock->product->category->name ?? '',
                        'slug' => $product_stock->product->category->slug ?? '',
                        'logo'=> uploaded_asset($product_stock->product->category->icon ?? ''),
                    ];
                }
    
                $photo_paths = explode(',',$product_stock->product->photos);
         
                $photos = $price_breakup = [];
                if (!empty($photo_paths)) {
                    foreach($photo_paths as $php){
                        $photos[] = get_product_image($php);
                    }
                }
                $price_breakup = array( "gold" => $product_stock->metal_price_break ?? 0,
                                        "making_charge" =>  $product_stock->making_price_break ?? 0,
                                        "stone_price" => $product_stock->stone_price ?? 0
                                );
               
                $response = [
                    'id' => (integer)$product_stock->id,
                    'name' => $product_stock->product->name,
                    'slug' => $product_stock->product->slug,
                    'product_type' => $product_stock->product->product_type,
                    'design' => $product_stock->product->design->name ?? '',
                    'design_category' => ucfirst($product_stock->product->design_category_id ?? ''),
                    'category' => $category,
                    'metal_type' => $product_stock->product->metal_type ?? '',
                    'purity' => $product_stock->product->purity ?? '',
                    'video_provider' => $product_stock->product->video_provider ?? '',
                    'video_link' => $product_stock->product->video_link != null ?  $product_stock->product->video_link : "",
                    'return_refund' =>  $product_stock->product->return_refund ,
                    'published' =>  $product_stock->product->published ,
                    'photos' => $photos,
                    'thumbnail_image' => get_product_image($product_stock->product->thumbnail_img),
                    'variant_image' => ($product_stock->image != NULL) ?  get_product_image($product_stock->image) : '' ,
                    'tags' => explode(',', $product_stock->product->tags),
                    'status' => $product_stock->status,
                    'sku' =>  $product_stock->sku,
                    'quantity' => $product_stock->qty ?? 0,
                    'description' => $product_stock->description,
                    'metal_weight' => $product_stock->metal_weight,
                    'stone_available' => $product_stock->stone_available,
                    'stone_type' =>  $product_stock->stone_type ?? null,
                    'stone_count' => $product_stock->stone_count,
                    'stone_weight' =>  $product_stock->stone_weight,
                    'stone_price' => $product_stock->stone_price,
                    'stroked_price' => $product_stock->price ?? 0,
                    'main_price' => $product_stock->offer_price ?? 0,
                    'price_breakup' => $price_breakup,
                    'offer_tag' =>  $product_stock->offer_tag,
                    'current_stock' => (integer)$product_stock->qty,
                    'rating' => (double)$product_stock->product->rating,
                    'rating_count' => (integer)Review::where(['product_id' => $product_stock->product_id])->count(),
                    'tabs' => $product_stock->product->tabs,
                    'meta_title' => $product_stock->product->seo->meta_title ?? '',
                    'meta_description' => $product_stock->product->seo->meta_description ?? '',
                    'meta_keywords' => $product_stock->product->seo->meta_keywords ?? '',
                    'og_title' => $product_stock->product->seo->og_title ?? '',
                    'og_description' => $product_stock->product->seo->og_description ?? '',
                    'twitter_title' => $product_stock->product->seo->twitter_title ?? '',
                    'twitter_description' => $product_stock->product->seo->twitter_description ?? '',
                    'current_attribute' => $curAttr,
                    'product_attributes' => $prodAttr,
                    'varient_products' => $varient_products
                ];
                return response()->json(['success' => true,"message"=>"Success","data" => $response],200);
            }else{
                return response()->json(['success' => false,"message"=>"No data","data" => []],200);
            }
        }else{
            return response()->json(['success' => false,"message"=>"No data","data" => []],200);
        } 
    }

    public function show($id)
    {
        // return new ProductDetailCollection(Product::where('id', $id)->get());
    }

    public function admin()
    {
        return new ProductCollection(Product::where('added_by', 'admin')->latest()->paginate(10));
    }

    public function seller($id, Request $request)
    {
        $shop = Shop::findOrFail($id);
        $products = Product::where('added_by', 'seller')->where('user_id', $shop->user_id);
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection($products->latest()->paginate(10));
    }

    public function category($id, Request $request)
    {
        $category_ids = CategoryUtility::children_ids($id);
        $category_ids[] = $id;

        $products = Product::whereIn('category_id', $category_ids)->physical();

        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }


    public function brand($id, Request $request)
    {
        $products = Product::where('brand_id', $id)->physical();
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }

        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function todaysDeal()
    {
        return Cache::remember('app.todays_deal', 86400, function () {
            $products = Product::where('todays_deal', 1)->physical();
            return new ProductMiniCollection(filter_products($products)->limit(20)->latest()->get());
        });
    }

    public function flashDeal()
    {
        return Cache::remember('app.flash_deals', 86400, function () {
            $flash_deals = FlashDeal::where('status', 1)->where('featured', 1)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->get();
            return new FlashDealCollection($flash_deals);
        });
    }

    public function featured()
    {
        $products = Product::where('featured', 1)->physical();
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function bestSeller()
    {
        return Cache::remember('app.best_selling_products', 86400, function () {
            $products = Product::orderBy('num_of_sale', 'desc')->physical();
            return new ProductMiniCollection(filter_products($products)->limit(20)->get());
        });
    }

    public function related($id)
    {
        return Cache::remember("app.related_products-$id", 86400, function () use ($id) {
            $product = Product::find($id);
            $products = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->physical();
            return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        });
    }

    public function topFromSeller($id)
    {
        return Cache::remember("app.top_from_this_seller_products-$id", 86400, function () use ($id) {
            $product = Product::find($id);
            $products = Product::where('user_id', $product->user_id)->orderBy('num_of_sale', 'desc')->physical();

            return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        });
    }


    public function search(Request $request)
    {
        $category_ids = [];
        $brand_ids = [];

        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }

        if ($request->brands != null && $request->brands != "") {
            $brand_ids = explode(',', $request->brands);
        }

        $sort_by = $request->sort_key;
        $name = $request->name;
        $min = $request->min;
        $max = $request->max;


        $products = Product::query();

        $products->where('published', 1)->physical();

        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }

        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }

            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }

            $products->whereIn('category_id', $category_ids);
        }

        if ($name != null && $name != "") {
            $products->where(function ($query) use ($name) {
                foreach (explode(' ', trim($name)) as $word) {
                    $query->where('name', 'like', '%' . $word . '%')->orWhere('tags', 'like', '%' . $word . '%')->orWhereHas('product_translations', function ($query) use ($word) {
                        $query->where('name', 'like', '%' . $word . '%');
                    });
                }
            });
            SearchUtility::store($name);
        }

        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }

        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }

        switch ($sort_by) {
            case 'price_low_to_high':
                $products->orderBy('unit_price', 'asc');
                break;

            case 'price_high_to_low':
                $products->orderBy('unit_price', 'desc');
                break;

            case 'new_arrival':
                $products->orderBy('created_at', 'desc');
                break;

            case 'popularity':
                $products->orderBy('num_of_sale', 'desc');
                break;

            case 'top_rated':
                $products->orderBy('rating', 'desc');
                break;

            default:
                $products->orderBy('created_at', 'desc');
                break;
        }

        return new ProductMiniCollection(filter_products($products)->paginate(10));
    }

    public function variantPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;

        if ($request->has('color') && $request->color != "") {
            $str = Color::where('code', '#' . $request->color)->first()->name;
        }

        $var_str = str_replace(',', '-', $request->variants);
        $var_str = str_replace(' ', '', $var_str);

        if ($var_str != "") {
            $temp_str = $str == "" ? $var_str : '-' . $var_str;
            $str .= $temp_str;
        }


        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;
        $stockQuantity = $product_stock->qty;


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

        if ($product->tax_type == 'percent') {
            $price += ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $price += $product->tax;
        }



        return response()->json([
            'product_id' => $product->id,
            'variant' => $str,
            'price' => (float)convert_price($price),
            'price_string' => format_price(convert_price($price)),
            'stock' => intval($stockQuantity),
            'image' => $product_stock->image == null ? "" : api_asset($product_stock->image)
        ]);
    }

    public function home()
    {
        return new ProductCollection(Product::inRandomOrder()->physical()->take(50)->get());
    }
}
