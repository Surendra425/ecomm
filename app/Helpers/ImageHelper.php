<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 11/1/18
 * Time: 2:36 PM
 */

namespace App\Helpers;


class ImageHelper
{
    public static function imageSave($image,$path,$user_img='', $destinationPath1 ='', $isPngtoJpg = 0)
    {
        if (!empty($image)) {

            $fileName = 'product_'.time().'_'.strtolower(str_random(6)).'.jpg';

            //$img = str_replace(" ", '-', $image->getClientOriginalName());
            //$img = explode('.', $img);
            //$input['imagename'] = ($isPngtoJpg ==1) ? $img[0] . '-' . time() .'_'. str_random(5) . '.jpg' : $img[0] . '-' . time() .'_'. str_random(5) . '.' . $img[1];
            $input['imagename'] = $fileName;


            $img = $path . '/' . $user_img;
            if (!empty($user_img)) {
                if($destinationPath1 != ''){
                    $img = $destinationPath1 . '/' . $user_img;
                    if (file_exists($img)) {
                        unlink($img);
                    }
                }

                if (file_exists($img)) {
                    unlink($img);
                }
            }


            if ($image->move($path, $input['imagename'])) {
                chmod($path.'/'.$fileName,0777);
                return $input['imagename'];
            }

            return false;
        }
    }

    public static function videoSave($image,$path,$user_img='', $destinationPath1 ='')
    {
        if (!empty($image)) {
            $fileName = 'video_'.time().'_'.strtolower(str_random(6)).'.'.$image->getClientOriginalExtension();

            //$img = str_replace(" ", '-', $image->getClientOriginalName());
            //$img = explode('.', $img);

            $input['imagename'] = $fileName;
            
            $img = $path . '/' . $user_img;
            if (!empty($user_img)) {
                if($destinationPath1 != ''){
                    $img = $destinationPath1 . '/' . $user_img;
                    if (file_exists($img)) {
                        unlink($img);
                    }
                }

                if (file_exists($img)) {
                    unlink($img);
                }
            }

            if ($image->move($path, $input['imagename'])) {
                return $input['imagename'];
            }

            return false;
        }
    }
  
}