<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryTranslation;
use App\Utility\CategoryUtility;
use Illuminate\Support\Str;
use Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $catgeory = null;
        $sort_search = null;
        $categories = Category::orderBy('order_level', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%' . $sort_search . '%');
        }
        if ($request->has('catgeory') && $request->catgeory !== '0') {
            $catgeory = $request->catgeory;
            $categories = $categories->whereHas('parentCategory', function ($q) use ($catgeory) {
                $q->where('id', $catgeory);
            });
        }
        $categories = $categories->paginate(30);
        return view('backend.product.categories.index', compact('categories', 'sort_search', 'catgeory'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->order_level = 0;
        if ($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->banner = $request->banner;
        $category->icon = $request->icon;

        // $category->meta_title = $request->meta_title;
        // $category->meta_description = $request->meta_description;

        $category->meta_title = $request->meta_title ?? $request->name;
        $category->meta_description = $request->meta_description ?? $request->name;
        $category->meta_keyword  = $request->meta_keywords;
        $category->og_title = $request->og_title ?? $request->meta_title;
        $category->og_description = $request->og_description ?? $request->meta_description;
        $category->twitter_title = $request->twitter_title ?? $request->meta_title;
        $category->twitter_description = $request->twitter_description ?? $request->meta_description;
        $category->footer_title = $request->footer_title;
        $category->footer_content = $request->footer_description;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        }

        // if ($request->slug != null) {
        //     $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        // }
        // else {
        //     $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        // }
        $category->is_active = $request->status;
        // $category->featured = $request->featured;
        // $category->top = $request->top;

        $category->slug = $request->slug;

        $category->save();

        $category->attributes()->sync($request->filtering_attributes);

        flash(translate('Category has been inserted successfully'))->success();
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $category = Category::findOrFail($id);
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=', $category->id)
            ->orderBy('name', 'asc')
            ->get();

        return view('backend.product.categories.edit', compact('category', 'categories', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $category->name = $request->name;
        }
        if ($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        // $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
      
        $category->meta_title = $request->meta_title ?? $request->name;
        $category->meta_description = $request->meta_description ?? $request->name;
        $category->meta_keyword  = $request->meta_keywords;
        $category->og_title = $request->og_title ?? $request->meta_title;
        $category->og_description = $request->og_description ?? $request->meta_description;
        $category->twitter_title = $request->twitter_title ?? $request->meta_title;
        $category->twitter_description = $request->twitter_description ?? $request->meta_description;
        $category->footer_title = $request->footer_title;
        $category->footer_content = $request->footer_description;

        $previous_level = $category->level;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        } else {
            $category->parent_id = 0;
            $category->level = 0;
        }
        $category->is_active = $request->status;
        // $category->featured = $request->featured;
        // $category->top = $request->top;

        if ($category->level > $previous_level) {
            CategoryUtility::move_level_down($category->id);
        } elseif ($category->level < $previous_level) {
            CategoryUtility::move_level_up($category->id);
        }

        // if ($request->slug != null) {
        //     $category->slug = strtolower($request->slug);
        // } else {
        //     $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . Str::random(5);
        // }

        $category->slug = $request->slug;

        $category->save();

        $category->allChildCategories()->update(['is_active' => $request->status]);

        $category->attributes()->sync($request->filtering_attributes);

        Cache::forget('featured_categories');
        flash(translate('Category has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->attributes()->detach();

        // Category Translations Delete
        foreach ($category->category_translations as $key => $category_translation) {
            $category_translation->delete();
        }

        foreach (Product::where('category_id', $category->id)->get() as $product) {
            $product->category_id = null;
            $product->save();
        }

        CategoryUtility::delete_category($id);
        Cache::forget('featured_categories');

        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('categories.index');
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        $category->save();
        Cache::forget('featured_categories');
        return 1;
    }

    public function updateStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->is_active = $request->status;
        $category->save();
        $category->allChildCategories()->update(['is_active' => $request->status]);
        // Cache::forget('featured_categories');
        return 1;
    }
}
