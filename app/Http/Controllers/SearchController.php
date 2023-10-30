<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Search;
use App\Models\Shop;
use App\Utility\CategoryUtility;
use Auth;
use Cache;

class SearchController extends Controller
{
    public function index(Request $request, $category_id = null, $brand_id = null)
    {
        // dd($request);
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;

        $conditions = [
            'published' => 1
        ];

        $products = Product::where($conditions);

        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        if ($request->brands != null) {
            $brand_ids = explode(',', $request->brands);
            $products->whereIn('brand_id', $brand_ids);
        }

        if ($request->rating != null) {
            $ratings = explode(',', $request->rating);

            foreach ($ratings as $rating) {
                $r = array(
                    $rating,
                    $rating + 1
                );
                $products->whereBetween('rating', $r);
            }
        }

        // Sidebar search
        $category_ids = array();
        if ($request->categories) {
            $categoryids = explode(',', $request->categories);
            $category_ids =  array_merge($category_ids, $categoryids);
        }

        // Header search
        if ($request->category) {
            $category_ids[] = $request->category;
        }

        // Category page
        if ($category_id != null) {
            $category_ids[] = $category_id;
        }

        if (!empty($category_ids)) {
            // dd($category_ids);
            foreach ($category_ids as $id) {
                $category_ids =  array_merge($category_ids, CategoryUtility::children_ids($id));
            }
            // dd($category_ids);
            $products->whereIn('category_id', $category_ids);
        }

        if ($min_price != null && $max_price != null) {
            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if ($query != null) {
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like',  $word);
                        });
                }
            });
        }



        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'name':
                $products->orderBy('name', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            default:
                $products->orderBy('created_at', 'desc');
                break;
        }

        $products = $products->select([
            'id',
            'name',
            'sku',
            'category_id',
            'brand_id',
            'thumbnail_img',
            'unit_price',
            'purchase_price',
            'rating',
            'slug',
            'discount',
            'discount_type',
            'discount_end_date',
            'discount_start_date',
            'hide_price',
        ])->where($conditions)->with('brand')->paginate(36)->appends(request()->query());

        $min_price_slider = convert_price(Product::min('unit_price'));
        $max_price_slider = convert_price(Product::max('unit_price'));

        $brands = Cache::rememberForever('brandsWithCount', function () {
            return Brand::select([
                'id',
                'name',
                'slug',
            ])->get();
        });

        $category = Cache::rememberForever('categoriesTree', function () {
            return CategoryUtility::getSidebarCategoryTree();
        });

        $selected_category = $request->category ?? 0;
        $side_categories = $request->categories ? explode(',', $request->categories) : [];
        $side_brands = $request->brands ? explode(',', $request->brands) : [];
        $side_rating = $request->rating ? explode(',', $request->rating) : [];

        return view('frontend.product.product_listing', compact('products', 'category', 'query', 'category_id', 'side_categories', 'side_brands', 'side_rating', 'selected_category', 'brand_id', 'sort_by', 'min_price', 'max_price', 'min_price_slider', 'max_price_slider', 'brands'));
    }

    public function listing(Request $request)
    {
        return $this->index($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->index($request, $category->id);
        }
        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        $brand = Brand::where('slug', $brand_slug)->first();
        if ($brand != null) {
            return $this->index($request, null, $brand->id);
        }
        abort(404);
    }

    //Suggestional Search
    public function ajax_search(Request $request)
    {
        $query = $request->keyword;

        $searchController = new SearchController;
        $searchController->store($request);

        $products = Product::query();

        $products = $products->where('published', 1)
            ->select([
                'id',
                'name',
                'sku',
                'category_id',
                'brand_id',
                'thumbnail_img',
                'unit_price',
                'purchase_price',
                'rating',
                'slug',
                'discount',
                'discount_type',
                'discount_end_date',
                'discount_start_date',
            ])
            ->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            })
            ->limit(8)
            ->get();

        $categories = Category::where('name', 'like', '%' . $query . '%')->get()->take(3);

        if (sizeof($categories) > 0 || sizeof($products) > 0) {
            // return compact('products', 'categories');

            return response()->json([
                'products' => $products,
                'categories' => $categories,
            ], 200);

            // return view('frontend.partials.search_content', compact('products', 'categories', 'keywords', 'shops'));
        }
        return '0';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $search = Search::where('query', $request->keyword)->first();

        // if (Auth::check()) {
        //     $user_id = Auth::id();
        // } else {
        //     $user_id = null;
        //     $temp_user_id = getTempUserId();
        // }

        Search::create([
            'query' => $request->keyword,
            'ip_address' => $request->ip(),
            'user_id' => Auth::id(),
            'temp_user_id' => Auth::check() ? null : getTempUserId(),
        ]);
    }
}
