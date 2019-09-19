<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
   public function index()
   {
       return view('welcome');
   }

   public function swagger()
   {
       return redirect('/swagger/index.html');
   }
}