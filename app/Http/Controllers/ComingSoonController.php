<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComingSoonController extends Controller
{
    public function index()
    {
        return view('app.coming_soon');        
    }
}
