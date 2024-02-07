<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Frontend\Banner;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Product;
use App\Models\Faqs;
use Cache;
use Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.website_settings.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $page = new Page;
        $page->title = $request->title;
        if (Page::where('slug', Str::slug($request->slug))->first() == null) {
            $page->slug             = Str::slug($request->slug);
            $page->type             = "custom_page";
            $page->content          = $request->content;
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->keywords         = $request->keywords;
            $page->meta_image       = $request->meta_image;
            $page->save();

            flash(translate('New page has been created successfully'))->success();
            return redirect()->route('website.pages');
        }

        flash(translate('Slug has been used already'))->warning();
        return back();
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
        $page_name = $request->page;
        $page = Page::where('type', $id)->first();
       
        if ($page != null) {
            if ($page->type == 'home_page') {
                $banners = Banner::where('status', 1)->get();
                $current_banners = BusinessSetting::whereIn('type', array('home_banner', 'home_mid_banner', 'home_large_banner'))->get()->keyBy('type');

                $categories = Cache::rememberForever('categories', function () {
                    return Category::where('parent_id', 0)->where('is_active',1)->with('childrenCategories')->get();
                });

                $products = Product::select('id', 'name')->get();
                $brands = Brand::all();

                return view('backend.website_settings.pages.home_page_edit', compact('page', 'banners', 'current_banners', 'categories', 'brands', 'products'));
            }
            elseif($page->type == 'terms_conditions' || $page->type == 'privacy_policy' || $page->type == 'return_refund'){
                return view('backend.website_settings.pages.policy', compact('page'));
            } 
            elseif ($page->type == 'product_listing') {
                return view('backend.website_settings.pages.product_listing', compact('page'));
            }
            elseif ($page->type == 'blog_list') {
                return view('backend.website_settings.pages.blog_listing', compact('page'));
            }
            elseif ($page->type == 'store_locator') {
                return view('backend.website_settings.pages.store_locator', compact('page'));
            }
            elseif ($page->type == 'about_us') {
                return view('backend.website_settings.pages.about_us', compact('page'));
            }
            elseif ($page->type == 'faq') {
                $questions = Faqs::orderBy('sort_order','asc')->get();
                return view('backend.website_settings.pages.faq', compact('page','questions'));
            }
            elseif ($page->type == 'contact_us') {
                return view('backend.website_settings.pages.contact_us', compact('page'));
            } 
            else {
                return view('backend.website_settings.pages.edit', compact('page'));
            }
        }
        abort(404);
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
        $page = Page::findOrFail($id);

        if (Page::where('id', '!=', $id)->where('type', $request->type)->first() == null) {
            if($request->has('slug')){
                $page->slug             = Str::slug($request->slug);
            }
            if($request->has('title')){
                $page->title            = $request->title;
            }
            if($request->has('content')){
                $page->content          = $request->content;
            }

            if($request->has('heading1')){
                $page->heading1         = $request->heading1;
            }

            if($request->has('page_image')){
                $page->image1           = $request->page_image;
            }

            if($request->has('heading2')){
                $page->heading2         = $request->heading2;
            }

            if($request->has('sub_heading1')){
                $page->sub_heading1     = $request->sub_heading1;
            }

            if($request->has('sub_heading2')){
                $page->sub_heading2     = $request->sub_heading2;
            }

            if($request->has('heading3')){
                $page->heading3         = $request->heading3;
            }

            $page->meta_title           = $request->meta_title;
            $page->meta_description     = $request->meta_description;
            $page->keywords             = $request->keywords;
            $page->meta_image           = $request->meta_image;

            $page->og_title             = $request->og_title;
            $page->og_description       = $request->og_description;

            $page->twitter_title        = $request->twitter_title;
            $page->twitter_description  = $request->twitter_description;

            $page->save();

            if($request->type == 'faq'){
                Faqs::truncate();
                $data = [];
                foreach ($request->faq as $value) {
                    if($value['question'] != '' && $value['answer'] != ''){
                        $data[] = array(
                            "question" => $value['question'] ?? NULL,
                            "answer"   => $value['answer'] ?? NULL,
                            "sort_order" =>  $value['sort_order'] ?? NULL,
                        );
                    }
                }
            
                if(!empty($data)){
                    Faqs::insert($data);
                }
            }

            flash(translate('Page has been updated successfully'))->success();
            return redirect()->route('website.pages');
        }

        flash(translate('Slug has been used already'))->warning();
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
        $page = Page::findOrFail($id);
        foreach ($page->page_translations as $key => $page_translation) {
            $page_translation->delete();
        }
        if (Page::destroy($id)) {
            flash(translate('Page has been deleted successfully'))->success();
            return redirect()->back();
        }
        return back();
    }

    public function show_custom_page($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        if ($page != null) {
            load_seo_tags($page);
            return view('frontend.custom_page', compact('page'));
        }
        abort(404);
    }

    public function mobile_custom_page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page != null) {
            return view('frontend.m_custom_page', compact('page'));
        }
        abort(404);
    }

    public function storeHomePageHighlights(Request $request){
        $request->validate([
            'heading4' => 'required',
            'sub_heading4' => 'required',
            'heading5' => 'required',
            'sub_heading5' => 'required',
            'heading6' => 'required',
            'sub_heading6' => 'required',
            'image1' => 'nullable|max:50',
            'image2' => 'nullable|max:50',
            'title1' => 'required',
            'title2' => 'required',
            'title3' => 'required',
            'image3' => 'nullable|max:50',
            'image4' => 'nullable|max:50',
            'image5' => 'nullable|max:50',

        ],[
            '*.required' => 'This field is required.',
            'image1.max' => "Maximum file size to upload is 50 KB.",
            'image2.max' => "Maximum file size to upload is 50 KB.",
            'image3.max' => "Maximum file size to upload is 50 KB.",
            'image4.max' => "Maximum file size to upload is 50 KB.",
            'image5.max' => "Maximum file size to upload is 50 KB.",
        ]);
    }
}
