<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntroductionController extends Controller
{
    public function show()
    {
        // ส่งค่าไปยัง View บทนำ1
        return view('บทนำ1');
    }
}
