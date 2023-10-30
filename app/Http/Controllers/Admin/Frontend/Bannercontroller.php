<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Frontend\Banner;
use App\Models\Product;
use Cache;
use Illuminate\Http\Request;

class Bannercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::paginate(15);
        return view('backend.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.banners.create');
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
            'banner' => 'required',
            'mobile_banner' => 'required',
            'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
        ], [
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);

        $banner = Banner::create([
            'name' => $request->name,
            'image' => $request->banner,
            'mobile_image' => $request->mobile_banner,
            'link_type' => $request->link_type,
            'link_ref' => $request->link_type,
            'link_ref_id' => $request->link_ref_id,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        Cache::forget('smallBanners');

        flash(translate('Banner created successfully'))->success();
        return redirect()->route('banners.index');
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
    public function edit(Banner $banner)
    {
        return view('backend.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'name' => 'required',
            'banner' => 'required',
            'mobile_banner' => 'required',
            'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
        ], [
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);

        $banner->update([
            'name' => $request->name,
            'image' => $request->banner,
            'mobile_image' => $request->mobile_banner,
            'link_type' => $request->link_type,
            'link_ref' => $request->link_type,
            'link_ref_id' => $request->link_ref_id,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        Cache::forget('smallBanners');

        flash(translate('Banner updated successfully'))->success();
        return redirect()->route('banners.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        Cache::forget('smallBanners');
        flash(translate('Banner has been deleted successfully'))->success();
        return redirect()->route('banners.index');
    }

    public function get_form(Request $request)
    {
        $old_data = $request->old_data ?? null;
        if ($request->link_type == "product") {
            $products = Product::select(['id', 'name'])->get();
            return view('partials.banners.banner_form_product', compact('products', 'old_data'));
        } elseif ($request->link_type == "category") {
            $categories = Category::where('parent_id', 0)
                ->with('childrenCategories')
                ->get();
            return view('partials.banners.banner_form_category', compact('categories', 'old_data'));
        } else {
            return view('partials.banners.banner_form', compact('old_data'));
        }
    }
}
