<?php
namespace App\Helpers;
use App\Product;
use Illuminate\Support\Facades\DB;
class NameHelper
{
    /* get name by id in array*/
    public static function getNameById($table ,$field, $columnName , $columnValue){
        //print_r($columnValue);die;
        $name = [];
        $data = DB::table($table)
        ->select($field)
        ->whereIn($columnName,$columnValue)
        ->get();
        foreach ($data as $list){
            $name[] = $list->{$field};
        }
        return $name;
    }
    /*get name by id*/
    public static function getNameBySingleId($table ,$field, $columnName , $columnValue){
        //print_r($columnValue);die;
        $data = DB::table($table)
        ->select($field)
        ->where($columnName,$columnValue)
        ->first();
        //echo "<pre>";print_r($data->{$field});die;
        return $data->{$field};
    }

    
}

