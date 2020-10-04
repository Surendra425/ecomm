<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;

use App\Plans;
use App\PlanOptions;

/**
 * Description of PlanHelper
 *
 * @author panchal
 */
class PlanHelper
{
    public function GetPlanListWithDetail()
    {
        $PlanList = Plans::where("status","Active")->get()->toArray();
        if (count($PlanList))
        {
            foreach ($PlanList as $key => $val)
            {
                $PlanList[$key]['Options']= PlanOptions::where("plan_id",$val['id'])->get()->toArray();
            }
        }
        return $PlanList;
    }
}
