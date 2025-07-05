<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengetahuanController extends Controller
{
   public function showmagang (){
           return view('magang.berbagipengetahuan');
   }
}
