<?php

namespace App\Http\Controllers\Search\Web;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('demo.home');
    }
}