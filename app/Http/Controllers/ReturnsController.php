<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StaticPages;
use Illuminate\Support\Facades\Auth;

class ReturnsController extends Controller
{
    public function index()
    {
    	 $customer = Auth::guard('customer')->user();
        $data = [];
        $data["customer"] = $customer;
        $data["return"] = StaticPages::where('page_name','Return')->first();
        return view('app.returns',$data);        
    }
}
