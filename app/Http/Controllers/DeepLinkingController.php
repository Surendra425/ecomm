<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeepLinkingController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Deep Link Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles .
     */

    /**
     * Accept contact us request for admin.
     *
     * @return json
     */
    public function deeplinking(Request $request)
    {     
        $data = [
          'applinks' => [
              "apps" => [],
              "details" => [
                  [
                      "appID" => "3JG79983KQ.com.ags.shopzz",
                      "paths" => [
                                  "/product-detail/*",
                                  "/event-details/*"
                                ]
                  ]
              ]
          ],
        ];
        
        return response()->json($data);
    }
}