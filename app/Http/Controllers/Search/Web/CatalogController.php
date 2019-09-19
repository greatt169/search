<?php

namespace App\Http\Controllers\Search\Web;

use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
   public function index()
   {
       return view('demo.catalog');
   }
}