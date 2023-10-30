<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Product;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $brands = Brand::orderBy('name', 'asc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $brands = $brands->where('name', 'like', '%' . $sort_search . '%');
        }
        $brands = $brands->paginate(15);
        return view('backend.product.brands.index', compact('brands', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.product.brands.create');
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
            'slug' => 'required|unique:brands,slug',
            'logo' => 'required',
        ]);

        $brand = new Brand;
        $brand->name = $request->name;

        $brand->meta_title = $request->meta_title ?? $request->name;
        $brand->meta_description = $request->meta_description ?? $request->name;
        $brand->meta_keywords  = $request->meta_keywords;

        $brand->og_title = $request->og_title ?? $request->meta_title;
        $brand->og_description = $request->og_description ?? $request->meta_description;

        $brand->twitter_title = $request->twitter_title ?? $request->meta_title;
        $brand->twitter_description = $request->twitter_description ?? $request->meta_description;

        $brand->footer_title = $request->footer_title;
        $brand->footer_content = $request->footer_description;

        $brand->slug = $request->slug;
        $brand->top = $request->top;

        $brand->logo = $request->logo;
        $brand->save();

        flash(translate('Brand has been inserted successfully'))->success();
        return redirect()->route('brands.index');
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
    public function edit(Request $request, Brand $brand)
    {
        return view('backend.product.brands.edit', compact('brand'));
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

        // dd($request->footer_description);

        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $id,
            'logo' => 'required',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;
        
        $brand->meta_title = $request->meta_title ?? $request->name;
        $brand->meta_description = $request->meta_description ?? $request->name;
        $brand->meta_keywords  = $request->meta_keywords;

        $brand->og_title = $request->og_title ?? $request->meta_title;
        $brand->og_description = $request->og_description ?? $request->meta_description;

        $brand->twitter_title = $request->twitter_title ?? $request->meta_title;
        $brand->twitter_description = $request->twitter_description ?? $request->meta_description;

        $brand->footer_title = $request->footer_title;
        $brand->footer_content = $request->footer_description;
        
        $brand->slug = $request->slug;
        $brand->logo = $request->logo;
        $brand->top = $request->top;
        $brand->save();

        flash(translate('Brand has been updated successfully'))->success();
        return redirect()->route('brands.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::where('brand_id', $id)->update([
            'brand_id' => 0
        ]);

        Brand::destroy($id);

        flash(translate('Brand has been deleted successfully'))->success();
        return redirect()->route('brands.index');
    }
}
