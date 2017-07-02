<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class appController extends Controller
{
    public function index(){
        return view('app');
    }
    public function toContent(){
        return view('content');
    }
}
