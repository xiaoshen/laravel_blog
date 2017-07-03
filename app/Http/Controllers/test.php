<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class test extends Controller
{
    //
    public function goback()
    {
        $name ="testword";
        return view('welcome')->witdh('name',$name);
    }

}
