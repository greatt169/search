<?php

namespace App\Demo\Controllers;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('demo.home');
    }
}