<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function index()
    {
        return view('site.index');
    }

    public function policy()
    {
        return view('site.policy');
    }

    public function terms()
    {
        return view('site.terms');
    }

    /*public function import()
    {
    }*/
}
