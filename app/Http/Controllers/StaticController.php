<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
class StaticController extends Controller
{
    public function home()
    {
        $feed_items=[];
        if (Auth::check()){
            $feed_items=Auth::user()->feed()->paginate(10);
        }
        return view('static.home',compact('feed_items'));
    }
    public function about()
    {
        return view('static.about');
    }
    public function help()
    {
        return view('static.help');
    }
}
