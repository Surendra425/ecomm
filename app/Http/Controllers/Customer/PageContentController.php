<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StaticPages;

/*
 |--------------------------------------------------------------------------
 | Home Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles home page.
 */

class PageContentController extends Controller
{
    /**
     * Show content page.
     *
     * @param Request $request
     * @param string $categorySlug
     *
     * @return view
     */
    public function getContent(Request $request)
    {
        $url = explode('/', $request->getUri());
        $pageSlug = end($url);
        $page = StaticPages::where('slug',$pageSlug)->first();
        
        if(!empty($page))
        {
            return view('front.pages.page', [
                'page' => $page
            ]);
        }

        abort(404);
    }
}