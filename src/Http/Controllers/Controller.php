<?php

namespace DarwinLuague\Translator\Http\Controllers;

use DarwinLuague\Translator\Http\Middleware\Authenticate;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }
}