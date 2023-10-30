<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Wishlist;
use App\Models\Category;
use App\Models\Product;
use Cache;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $wishlists = Wishlist::where('user_id', Auth::user()->id)->with([
                'product' => function ($query) {
                    return $query->select([
                        'id',
                        'name',
                        'thumbnail_img',
                        'unit_price',
                        'discount',
                        'discount_type',
                        'discount_start_date',
                        'discount_end_date',
                        'slug',
                    ]);
                }
            ])->paginate(10);
            return view('frontend.user.view_wishlist', compact('wishlists'));
        }
        // return redirect()->route('user.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $product_id = Product::select('id')->where('slug', $request->slug)->firstOrFail()->id;
            $wishlist = Wishlist::firstOrCreate([
                'user_id' => Auth::user()->id,
                'product_id' => $product_id
            ]);

            if ($wishlist->wasRecentlyCreated) {
                Cache::forget('user_wishlist_count_' . Auth::id());
                return response()->json([
                    'message' => 'Item added to wishlist',
                    'count' => wishListCount(),
                ], 200);
            }

            return response()->json([
                'message' => 'Item is already in your wishlist',
                'count' => wishListCount(),
            ], 200);
        }

        return response()->json(['message' => 'Please login first'], 401);
    }

    public function remove(Request $request)
    {

        if (Auth::check()) {
            $wishlist = Wishlist::where([
                'id' => $request->id,
                'user_id' => Auth::id()
            ])->firstOrFail();

            if ($wishlist->delete()) {
                Cache::forget('user_wishlist_count_' . Auth::id());
                return json_encode([
                    'status' => 200,
                    'count' => wishListCount()
                ]);
            }
        }

        return response()->json(['message' => 'Something went wrong, please try again'], 404);
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
    public function edit($id)
    {
        //
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
        //
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
