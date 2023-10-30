<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use DB;
use Illuminate\Http\Request;

class AbandonedCartController extends Controller
{
    public function index(Request $request)
    {
        $query = Cart::whereStatus(0)->latest()->groupBy(DB::raw('COALESCE(`carts`.`user_id`,`carts`.`temp_user_id`)'));
        if ($request->start_date !== '' && $request->start_date !== null) {
            $end_date = ($request->end_date !== '' && $request->end_date !== null) ? $request->end_date : $request->start_date;
            $query->whereBetween('created_at', [$request->start_date, $end_date]);
        }
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        $carts = $query->with(['user'])->paginate(15);
        return view('backend.reports.abandoned_cart', compact('carts'));
    }

    public function view(Cart $cart)
    {
        $query = Cart::whereStatus(0)->with(['product'])->latest();

        if ($cart->user_id) {
            $query->where('user_id', $cart->user_id);
            $query->with(['user']);
        } else {
            $query->where('temp_user_id', $cart->temp_user_id);
        }
        $carts = $query->get();

        $total_quantity =  $carts->sum('quantity');
        $total_price = 0;

        foreach ($carts as $cart) {
            $total_price += $cart->quantity * $cart->price;
        }

        return view('backend.reports.abandoned_cart_details', compact('carts', 'total_quantity', 'total_price'));
    }
}
