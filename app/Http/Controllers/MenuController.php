<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Harimayco\Menu\Models\MenuItems;
use App\Models\Message;
use Auth;

class MenuController extends Controller
{
    public function index()
    {
        return view('backend.website_settings.menu');
    }
}
