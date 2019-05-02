<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $data = include_once('data.php');
        dd($data);
    }
}
