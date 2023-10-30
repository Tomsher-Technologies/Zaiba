<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\HomeSlider;
use App\Http\Requests\StoreHomeSliderRequest;
use App\Http\Requests\UpdateHomeSliderRequest;
use Cache;
use Illuminate\Http\Request;

class HomeSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = HomeSlider::paginate(15);
        return view('backend.home_sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.home_sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreHomeSliderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHomeSliderRequest $request)
    {
        $slider = HomeSlider::create([
            'name' => $request->name,
            'image' => $request->banner,
            'mobile_image' => $request->mobile_banner,
            'link_type' => $request->link_type,
            'link_ref' => $request->link_type,
            'link_ref_id' => $request->link_ref_id,
            'link' => $request->link,
            'sort_order' => $request->sort_order,
            'status' => $request->status,
        ]);

        Cache::forget('homeSlider');

        flash(translate('Slider created successfully'))->success();
        return redirect()->route('home-slider.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Frontend\HomeSlider  $homeSlider
     * @return \Illuminate\Http\Response
     */
    public function show(HomeSlider $homeSlider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Frontend\HomeSlider  $homeSlider
     * @return \Illuminate\Http\Response
     */
    public function edit(HomeSlider $homeSlider)
    {
        return view('backend.home_sliders.edit', compact('homeSlider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateHomeSliderRequest  $request
     * @param  \App\Models\Frontend\HomeSlider  $homeSlider
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHomeSliderRequest $request, HomeSlider $homeSlider)
    {
        $homeSlider->update([
            'name' => $request->name,
            'image' => $request->banner,
            'mobile_image' => $request->mobile_banner,
            'link_type' => $request->link_type,
            'link_ref' => $request->link_type,
            'link_ref_id' => $request->link_ref_id,
            'link' => $request->link,
            'sort_order' => $request->sort_order,
            'status' => $request->status,
        ]);

        Cache::forget('homeSlider');

        flash(translate('Slider updated successfully'))->success();
        return redirect()->route('home-slider.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Frontend\HomeSlider  $homeSlider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        HomeSlider::destroy($id);
        Cache::forget('homeSlider');
        flash(translate('Slider deleted successfully'))->success();
        return redirect()->route('home-slider.index');
    }

    public function updateStatus(Request $request)
    {
        $slider = HomeSlider::findOrFail($request->id);
        Cache::forget('homeSlider');
        $slider->status = $request->status;
        if ($slider->save()) {
            return 1;
        }
        return 0;
    }
}
