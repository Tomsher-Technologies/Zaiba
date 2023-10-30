<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductEnquiries;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EnquiriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ProductEnquiries::whereStatus(1)->withCount('products')->latest();

        $date = '';

        if ($request->date) {
            $date_array = explode(' to ', $request->date);
            $start_date = $date_array[0];
            $start_date = Carbon::parse($start_date)->startOfDay();
            $end_date = isset($date_array[1]) ? $date_array[1] : $date_array[0];
            $end_date = Carbon::parse($start_date)->endOfDay();
            $query->whereBetween('created_at', [$start_date, $end_date]);
            $date = $request->date;
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $users = User::where('user_type', 'customer')->get();
        $search_user = $request->user_id;

        $enquiries = $query->with('user')->paginate(15);

        return view('backend.sales.enquiries.index', compact('enquiries', 'date', 'users', 'search_user'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $enquiry = ProductEnquiries::with(['products' => function ($query) {
            $query->without(['product_translations', 'taxes']);
        }, 'user'])->findOrFail(decrypt($id));

        // if ($enquiry->status == 0) {
        //     $enquiry->status = 1;
        //     $enquiry->save();
        // }
        return view('backend.sales.enquiries.show', compact('enquiry'));
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
        $enquiry = ProductEnquiries::find($id)->first();
        if ($enquiry) {

            $enquiry->products()->sync([]);
            $enquiry->destroy();

            flash(translate('Enqrury deleted'))->success();
            return back();
        }
        flash(translate('Somthing went wrong, please try again'))->error();
        return back();
    }
}
