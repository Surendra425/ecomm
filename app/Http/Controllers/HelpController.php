<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StaticPages;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    public function index()
    {
    	 $customer = Auth::guard('customer')->user();
        $data = [];
        $data["customer"] = $customer;
        $data["help"] = StaticPages::where('page_name','Help')->first();
        return view('app.help',$data);        
    }
}
