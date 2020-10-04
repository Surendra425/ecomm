<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Store;
use App\Collections;
use App\CollectionProducts;

class StoreController extends Controller
{

    public function index()
    {
        
    }

    public function show(Request $request, Store $store)
    {
        dd($store);
    }

}
