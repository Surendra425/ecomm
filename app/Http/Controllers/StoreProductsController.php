<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PlanHelper;
use App\Store;
use App\StoreFollower;
use App\Collections;
use App\CollectionProducts;
use App\VendorProductCategory;
use App\Product;
use App\ProductLike;
use App\ProductCategory;
use App\ProductAttrCombination;
use Illuminate\Support\Facades\Redirect;

class StoreProductsController extends Controller
{
    
    public function show(Product $product)
    {
        dd($product);
    }
}
