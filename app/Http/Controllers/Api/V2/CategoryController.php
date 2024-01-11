<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CategoryCollection;
use App\Models\BusinessSetting;
use App\Models\Category;
use Cache;

class CategoryController extends Controller
{

    public function index($parent_id = 0)
    {
        $categoryList = Category::where('parent_id', $parent_id)->get();
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
    }

    public function featured()
    {
        return Cache::remember('app.featured_categories', 86400, function () {
            return new CategoryCollection(Category::where('featured', 1)->get());
        });
    }

    public function home()
    {
        return Cache::remember('app.home_categories', 86400, function () {
            return new CategoryCollection(Category::whereIn('id', json_decode(get_setting('home_categories')))->get());
        });
    }

    public function top()
    {
        return Cache::remember('app.top_categories', 86400, function () {
            return new CategoryCollection(Category::whereIn('id', json_decode(get_setting('home_categories')))->limit(20)->get());
        });
    }
}
