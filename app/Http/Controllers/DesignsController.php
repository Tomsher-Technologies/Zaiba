<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designs;
use Illuminate\Support\Str;

class DesignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $designs = Designs::orderBy('name', 'asc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $designs = $designs->where('name', 'like', '%' . $sort_search . '%');
        }
        $designs = $designs->paginate(15);
        return view('backend.product.designs.index', compact('designs', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.product.designs.create');
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
            'slug' => 'required|unique:designs,slug',
            'image' => 'required',
        ]);
       
        $design = new Designs;
        $design->name = $request->name;

        $design->meta_title = $request->meta_title ?? $request->name;
        $design->meta_description = $request->meta_description ?? $request->name;
        $design->meta_keywords  = $request->meta_keywords;

        $design->og_title = $request->og_title ?? $request->meta_title;
        $design->og_description = $request->og_description ?? $request->meta_description;

        $design->twitter_title = $request->twitter_title ?? $request->meta_title;
        $design->twitter_description = $request->twitter_description ?? $request->meta_description;
        $design->slug = $request->slug;
        $design->is_active = $request->is_active;

        $design->logo = $request->image;
        $design->save();

        flash(translate('Design has been inserted successfully'))->success();
        return redirect()->route('designs.index');
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
    public function edit(Request $request, Designs $design)
    {
        return view('backend.product.designs.edit', compact('design'));
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
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:designs,slug,' . $id,
            'logo' => 'required',
        ]);

        $design = Designs::findOrFail($id);
        $design->name = $request->name;
        $design->meta_title = $request->meta_title ?? $request->name;
        $design->meta_description = $request->meta_description ?? $request->name;
        $design->meta_keywords  = $request->meta_keywords;
        $design->og_title = $request->og_title ?? $request->meta_title;
        $design->og_description = $request->og_description ?? $request->meta_description;
        $design->twitter_title = $request->twitter_title ?? $request->meta_title;
        $design->twitter_description = $request->twitter_description ?? $request->meta_description;
        $design->slug = $request->slug;
        $design->logo = $request->logo;
        $design->is_active = $request->is_active;
        $design->save();

        flash(translate('Design has been updated successfully'))->success();
        return redirect()->route('designs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
