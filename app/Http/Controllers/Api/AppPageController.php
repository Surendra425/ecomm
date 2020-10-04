<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StaticPages;


class AppPageController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | App Page Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles static page contents
     */

    /**
     * Gets terms and condition page.
     *
     * @return json
     */
    public function getTermConditions(Request $request)
    {

        $colName = !empty($request->isAr) ? 'ifnull(description_ar,description)' : 'description';
        $dir = !empty($request->isAr) ? 'rtl' : 'ltr';

        $termsCondition = StaticPages::where('id',2)->selectRaw($colName.' AS description')->first();

        if(!empty($termsCondition))
        {
            $html = '<html>
                    <head>
                    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
                    </head>
                    <body dir="'.$dir.'" lang="ar">
                    ';
            $endHtml = '</body>
                        </html>';

            return $this->toJson([
                'content' => $html.$termsCondition->description.$endHtml,
            ]);
        }
        
        return $this->toJson([], trans('api.page.not_available'),0);
        
    }
    
    /**
     * Gets privacy policy page.
     *
     * @return json
     */
    public function getPrivacyPolicy(Request $request)
    {
        $colName = !empty($request->isAr) ? 'ifnull(description_ar,description)' : 'description';

        $privacyPolicy = StaticPages::where('id',3)->selectRaw($colName.' AS description')->first();
        $dir = !empty($request->isAr) ? 'rtl' : 'ltr';
        
        $html = '<html>
                    <head>
                    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
                    </head>
                    <body dir="'.$dir.'" lang="ar">
                    ';
        $endHtml = '</body>
                        </html>';

        if(!empty($privacyPolicy))
        {
            return $this->toJson([
                'content' => $html.$privacyPolicy->description.$endHtml,
            ]);
        }
        
        return $this->toJson([], trans('api.not_available'),0);
    }
}
