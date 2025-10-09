<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home(){
        return view('pages.home');
    }

    public function absent(){
        return view('pages.absensi-list');
    }
}
