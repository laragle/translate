<?php

namespace Laragle\Translate\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Laragle\Translate\Http\Middleware\Authenticate;

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