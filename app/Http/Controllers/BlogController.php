<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $blogs = Blog::orderBy('created_at', 'desc');
        
        if ($request->search != null){
            $blogs = $blogs->where('title', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $blogs = $blogs->paginate(15);

        return view('backend.blog_system.blog.index', compact('blogs','sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.blog_system.blog.create');
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
            'title' => 'required|max:255',
            'slug' => 'required',
            'image' => 'required|max:200'
        ],[
            '*.uploaded' => 'File size should be less than 200 KB',
            '*.required' => 'This field is required'
        ]);

        $slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        $same_slug_count = Blog::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $blogImage = null;
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $filename =    strtolower(Str::random(2)).time().'.'. $uploadedFile->getClientOriginalName();
            $name = Storage::disk('public')->putFileAs(
                'blogs',
                $uploadedFile,
                $filename
            );
           $blogImage = Storage::url($name);
        } 
        $blog                       = new Blog;
        $blog->title                = $request->title ?? NULL;
        $blog->image                = $blogImage;
        $blog->blog_date            = ($request->has('blog_date') && $request->blog_date != '') ? $request->blog_date : date('Y-m-d');
        $blog->slug                 = $slug;
        $blog->description          = $request->description ?? NULL;
        $blog->seo_title            = $request->meta_title ?? NULL;
        $blog->og_title             = $request->og_title ?? NULL;
        $blog->twitter_title        = $request->twitter_title ?? NULL;
        $blog->seo_description      = $request->meta_description ?? NULL;
        $blog->og_description       = $request->og_description ?? NULL;
        $blog->twitter_description  = $request->twitter_description ?? NULL;

        $keywords = array();
        if ($request->meta_keywords[0] != null) {
            foreach (json_decode($request->meta_keywords[0]) as $key => $keyword) {
                array_push($keywords, $keyword->value);
            }
        }

        $blog->keywords             = implode(',', $keywords);
        $blog->save();

        flash(translate('Blog post has been created successfully'))->success();
        return redirect()->route('blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::find($id);
        
        return view('backend.blog_system.blog.edit', compact('blog'));
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
            'title' => 'required|max:255',
            'slug' => 'required',
            'image' => 'nullable|max:200'
        ],[
            '*.uploaded' => 'File size should be less than 200 KB',
            '*.required' => 'This field is required'
        ]);

        $blog = Blog::find($id);

        $blog->title                = $request->title ?? NULL;
        $blog->blog_date            = ($request->has('blog_date') && $request->blog_date != '') ? $request->blog_date : date('Y-m-d');
        $blog->slug                 = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        $blog->description          = $request->description ?? NULL;
        $blog->seo_title            = $request->meta_title ?? NULL;
        $blog->og_title             = $request->og_title ?? NULL;
        $blog->twitter_title        = $request->twitter_title ?? NULL;
        $blog->seo_description      = $request->meta_description ?? NULL;
        $blog->og_description       = $request->og_description ?? NULL;
        $blog->twitter_description  = $request->twitter_description ?? NULL;

        $keywords = array();
        if ($request->meta_keywords[0] != null) {
            foreach (json_decode($request->meta_keywords[0]) as $key => $keyword) {
                array_push($keywords, $keyword->value);
            }
        }

        $blogImage = $blog->image ?? null;
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $filename =    strtolower(Str::random(2)).time().'.'. $uploadedFile->getClientOriginalName();
            $name = Storage::disk('public')->putFileAs(
                'blogs',
                $uploadedFile,
                $filename
            );

            if($blogImage != null){
                $filePath = Str::remove('/storage/', $blogImage);
                
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
           $blogImage = Storage::url($name);
        } 

        $blog->image                = $blogImage;
        $blog->keywords             = implode(',', $keywords);
        $blog->save();

        flash(translate('Blog post has been updated successfully'))->success();
        return redirect()->route('blog.index');
    }
    
    public function change_status(Request $request) {
        $blog = Blog::find($request->id);
        $blog->status = $request->status;
        
        $blog->save();
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);
         
        $blogImage = $blog->image ?? null;
        if($blogImage != null){
            $filePath = Str::remove('/storage/', $blogImage);
            
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
        
        $blog->delete();
        flash(translate('Blog post has been deleted successfully'))->success();
        return redirect()->route('blog.index');
    }


    public function all_blog() {
        $blogs = Blog::where('status', 1)->orderBy('created_at', 'desc')->paginate(12);
        return view("frontend.blog.listing", compact('blogs'));
    }
    
    public function blog_details($slug) {
        $blog = Blog::where('slug', $slug)->first();
        return view("frontend.blog.details", compact('blog'));
    }
}
