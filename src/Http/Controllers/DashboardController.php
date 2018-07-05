<?php

namespace DarwinLuague\Translator\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        return view('translator::master');
    }
}