<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Cache;
use Harimayco\Menu\Models\MenuItems;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
	public function header(Request $request)
	{
		return view('backend.website_settings.header');
	}
	public function footer(Request $request)
	{
		$lang = $request->lang;
		return view('backend.website_settings.footer', compact('lang'));
	}
	public function pages(Request $request)
	{
		return view('backend.website_settings.pages.index');
	}
	public function appearance(Request $request)
	{
		return view('backend.website_settings.appearance');
	}
	public function menu(Request $request)
	{
		return view('backend.website_settings.menu');
	}

	public function menuUpdate(Request $request)
	{
		// return response()->json(  , 200);

		$brands = NULL;
		if ($request->brands) {
			$brands = implode(',', $request->brands);
		}

		MenuItems::where('id', $request->id)->update([
			'img_1' => $request->img_1,
			'img_2' => $request->img_2,
			'img_3' => $request->img_3,

			'img_1_link' => $request->img_1_link,
			'img_2_link' => $request->img_2_link,
			'img_3_link' => $request->img_3_link,

			'brands' => $brands
		]);


		Cache::forget('menu_' . $request->menu_id);

		return response()->json('completed', 200);
	}
}
