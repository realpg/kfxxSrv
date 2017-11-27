<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{

    //登录页面
    public function home()
    {
        return view('demo/home');
    }

}
