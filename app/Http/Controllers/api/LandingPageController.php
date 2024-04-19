<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('landing_page');
    }
}