<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Products\ProductEnquiries;
use Auth;
use Cache;
use Illuminate\Http\Request;

class EnquiryContoller extends Controller
{

    protected $user_col = "";
    protected $user_id = "";

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                $this->user_col = "user_id";
                $this->user_id = Auth::id();
            } else {
                $this->user_col = "temp_user_id";
                $this->user_id = getTempUserId();
            }

            return $next($request);
        });
    }

    public function index()
    {
        $enquiries = ProductEnquiries::whereStatus(0)->where($this->user_col, $this->user_id)->with('products')->first();
        return view('frontend.enquiry.enquiry', compact('enquiries'));
    }

    public function add(Request $request)
    {
        $product = Product::select('id')->where('slug', $request->slug)->with([
            'stocks'
        ])->first();

        $enquiries = ProductEnquiries::whereStatus(0)->where($this->user_col, $this->user_id)->first();

        if (!$enquiries) {
            $enquiries = ProductEnquiries::create([
                'status' => 0,
                $this->user_col => $this->user_id,
                'comment' => ""
            ]);
        }

        $enquiries->products()->syncWithoutDetaching([
            $product->id => [
                'sku' => $product->stocks->first()->sku,
                'varient' => $product->stocks->first()->variant,
            ]
        ]);

        Cache::flush('user_enquiry_count_' . $this->user_id);

        return response()->json([
            'message' => "Product added to enquiry",
            'count' => enquiryCount()
        ], 200);
    }

    public function remove(Request $request)
    {
        $enquiries = ProductEnquiries::whereStatus(0)->where($this->user_col, $this->user_id)->first();
        $enquiries->products()->detach($request->id);
        Cache::flush('user_enquiry_count_' . $this->user_id);
        return response()->json([
            'message' => "Product removed from enquiry",
            'count' => enquiryCount()
        ], 200);
    }

    public function submit(Request $request)
    {
        parse_str($request->data, $data);

        $enquiries = ProductEnquiries::whereStatus(0)->where($this->user_col, $this->user_id)->first();

        $enquiries->update([
            'comment' => $data['message'],
            'status' => 1,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone'],
        ]);

        Cache::flush('user_enquiry_count_' . $this->user_id);

        return response()->json([
            'message' => "Enquriy sent succesfully",
            'count' => 0
        ], 200);
    }
}
