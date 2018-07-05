<?php

namespace Laragle\Translate\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        return view('translate::master');
    }
}