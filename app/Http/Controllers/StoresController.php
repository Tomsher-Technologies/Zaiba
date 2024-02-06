<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stores;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Validator;

class StoresController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        if ($request->has('search')) {
            $sort_search = $request->search;
        }

        $query = Stores::select("*");
        if($sort_search){  
            $query->Where(function ($query) use ($sort_search) {
                    $query->orWhere('name', 'LIKE', "%$sort_search%")
                    ->orWhere('address', 'LIKE', "%$sort_search%")
                    ->orWhere('phone', 'LIKE', "%$sort_search%")
                    ->orWhere('email', 'LIKE', "%$sort_search%");
            });                    
        }
                        
        $query->orderBy('id','DESC')->get();

        $stores = $query->paginate(20);
        return view('backend.stores.index', compact('stores', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.stores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'address'   => 'required',
            'phone'     => 'nullable|numeric',
            'email'     => 'nullable|email',
            'lat'       => 'required',
            'long'      => 'required'
        ]);
 
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $store = Stores::create([
            'name'          => $request->name ?? null,
            'address'       => $request->address ?? null,
            'phone'         => $request->phone ?? null,
            'email'         => $request->email ?? null,
            'working_hours' => $request->working_hours ?? null,
            'latitude'      => $request->lat ?? null,
            'longitude'     => $request->long ?? null,
            'status'        => 1,
        ]);
        
        flash(translate('Store has been created successfully'))->success();
        return redirect()->route('admin.stores.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit($store)
    {
        $stores = Stores::where('id','=',$store)->get();
               
        return view('backend.stores.edit', compact('stores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'address'   => 'required',
            'phone'     => 'nullable|numeric',
            'email'     => 'nullable|email',
            'lat'       => 'required',
            'long'      => 'required'
        ]);
 
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $store                  = Stores::find($id);
        $store->name            = $request->name ?? null;
        $store->address         = $request->address ?? null;
        $store->phone           = $request->phone ?? null;
        $store->email           = $request->email ?? null;
        $store->working_hours   = $request->working_hours ?? null;
        $store->latitude        = $request->lat ?? null;
        $store->longitude       = $request->long ?? null;
        $store->status          = ($request->has('status')) ? 1 :0;
      
        if($store->save()) {
            flash(translate('Store details has been updated successfully'))->success();
        }else{
            flash(translate('Something went wrong'))->error();
        }
        return redirect()->route('admin.stores.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        // $store->delete();
        // flash(translate('Store has been successfully deleted'))->success();
        // return redirect()->route('admin.stores.index');
    }

}
