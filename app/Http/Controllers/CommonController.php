<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 18/1/18
 * Time: 5:02 PM
 */

namespace App\Http\Controllers;

use App\Admin;
use App\Helpers\ImageHelper;
use App\Product;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use App\Keywords;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;
use Pbmedia\LaravelFFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\File;

class CommonController extends Controller
{
    /**
     * check unique name.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUnique(Request $request, $table, $columnName)
    {
        //if($request->ajax()) {
            if(!empty($request->value)) {

                $where = [
                    [$columnName, '=', $request->value],
                ];

                if(!empty($request->id)) {
                    $where[] = ['id', '!=', $request->id];
                }

                $count = DB::table($table)
                    ->where($where)
                    ->count();

                return $count > 0 ?  'false' : 'true';
            }

        //}
    }
    public function uniqueNotGuest(Request $request, $table, $columnName)
    {
        //if($request->ajax()) {
        if(!empty($request->value)) {

            $where = [
                [$columnName, '=', $request->value],
                ['type', '!=', 'guest']
            ];
            
            if(!empty($request->id)) {
                $where[] = ['id', '!=', $request->id];
            }

            $count = DB::table($table)
                ->where($where)
                ->count();

            return $count > 0 ?  'false' : 'true';
        }

        //}
    }
public function checkUniqueVendor(Request $request, $table, $columnName)
    {
        //if($request->ajax()) {
        if(!empty($request->value)) {

            $where = [
                [$columnName, '=', $request->value],
                ['type', '!=', 'guest']
            ];

            if(!empty($request->id)) {
                $where[] = ['id', '!=', $request->id];
            }

            $count = DB::table($table)
                ->where($where)
                ->count();

            return $count > 0 ?  'false' : 'true';
        }elseif (!empty($request->email)){
            $where = [
                [$columnName, '=', $request->value],
            ];

            if(!empty($request->id)) {
                $where[] = ['id', '!=', $request->id];
            }

            $count = DB::table($table)
                ->where($where)
                ->count();

            return $count > 0 ?  'false' : 'true';
        }

        //}
    }

    /*public function getDataById(Request $request, $table, $columnName){
       if(!empty($request->value)) {
            $where = [
                [$columnName, '=', $request->value],
                ['status', "Active"],
            ];
            $data = DB::table($table)
                ->where($where)
                ->get();
                //echo "<pre>";print_r($data);die;
            return json_encode($data);
        }
    }*/
    public function getDataById(Request $request, $table, $columnName){
        if(!empty($request->value)) {
            $where = [
                [$columnName, '=', $request->value],
                ['status', "Active"],
            ];
            if($table == 'city'){
                $data = DB::table($table)
                    ->where($where)
                    ->orderBy('city_name','ASC')
                    ->get();
            }else{
                $data = DB::table($table)
                    ->where($where)
                    ->get();
            }

            //echo "<pre>";print_r($data);die;
            return json_encode($data);
        }
    }
    public function getShippingData(Request $request, $table, $columnName){
        if(!empty($request->value)) {

            $ids = explode(',',$request->value);
            $data = DB::table($table)
                ->whereIn($columnName,$ids)
                ->get();
            //echo "<pre>";print_r($data);die;
            return json_encode($data);
        }
    }
    public function getShippingClass(Request $request, $table, $columnName){
        if(!empty($request->value)) {
            $where = [
                [$columnName, '=', $request->value],
                ['status', "Active"],
            ];
            if(!empty($request->id)) {
                $where[] = ['vendor_id', '=', $request->id];
            }

            $data = DB::table($table)
                ->select('shipping_class',DB::raw('group_concat(id) as id'))
                ->where($where)
                ->groupBy('shipping_class')
                ->get();
           // echo "<pre>";print_r($data);die;
            return json_encode($data);
        }
    }

    /**
     * Get store category based on vendors
     * @param Request $request
     * @param $table
     * @param $columnName
     * @return string
     */
    public function getproductDataById(Request $request, $table, $columnName){

        if(!empty($request->value)) {
             $where = [
                ['status', '=', 'Active'],
            ];
            if(!empty($request->id)) {
                $where[] = ['vendor_id', '=', $request->id];
            }
           $admin = Admin::select('id')->where('status',1)->first();

            $data = DB::table($table)
                ->whereIn($columnName, array($admin->id,$request->value))
                ->distinct()
                ->get();
           // echo "<pre>";print_r($data);
            return json_encode($data);
        }
    }
    public function getVendorShippingDetails(Request $request, $table, $columnName){
        if(!empty($request->value)) {
            $where = [
                [$columnName, '=', $request->value]
            ];
            $data = DB::table($table)
                ->where($where)
                ->groupBy('country_id')
                ->get();
            //echo "<pre>";print_r($data);die;
            return json_encode($data);
        }
    }
    public function getCityById(Request $request, $table, $columnName){

         if(!empty($request->value)) {
             $where = [
                 [$columnName, '=', $request->value],
                 ['status', '=', 'Active'],
             ];
             $data = DB::table($table)
                 ->where($where)
                 ->get();
             $stateId = [];
             foreach($data as $item){
                 $stateId[] = $item->id;
             }

            $city = DB::table('city')
                ->whereIn('state_id',$stateId)
                ->get();
             //echo "<pre>";print_r($city);die;
             return json_encode($city);
        }
    }
    public function getkeyword(Request $request){
        $query = Keywords::select('keyword','id')->get();
        $data = json_decode(json_encode($query));
        echo json_encode($data);
        exit;
    }

    /**
     * Product image uploading
     * @param Request $request
     * @return string
     */
    public function uploadImage(Request $request){
         set_time_limit(500);

        $image = Input::file('file');
        //echo "<pre>";print_r($image);die;
        $destinationPath = public_path('doc/product_image_temp');
        $img = getimagesize($image);
        /*echo "$img[0]";echo "$img[1]";print_r($img);
        die;*/
        $user_img = '';

        $data = ImageHelper::imageSave($image,$destinationPath,$user_img, '', 1);
        if($data){
            
            $imgUrl = public_path('doc/product_image_temp/').'/'.$data;
            $Path = public_path('doc/product_image');
           if($img[0] < 200 || $img[1] < 200 ){
               $thumb_img = Image::make($imgUrl)->opacity(0)->resizeCanvas(255, 255, 'center', false, 'ffffff');
               $main_img = Image::make($imgUrl)->opacity(0)->resizeCanvas(540, 540, 'center', false, 'ffffff');
           }else{
               $thumb_img = Image::make($imgUrl)->resize(255, 255, function ($constraint) {
                   $constraint->aspectRatio();} );
               $main_img = Image::make($imgUrl)->resize(540, 540, function ($constraint) {
                   $constraint->aspectRatio();} );

               // Fill up the blank spaces with transparent color
               $thumb_img->opacity(0)->resizeCanvas(255, 255, 'center', false, array(255, 255, 255, 0));
               $main_img->opacity(0)->resizeCanvas(540, 540, 'center', false, array(255, 255, 255, 0));
           }
            /* if($img[0] >= 540 && $img[1] >= 540 ){
            $thumb_img = Image::make($imgUrl)->fit(255, 255, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();} );
                $main_img = Image::make($imgUrl)->fit(540, 540, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();} );

            }*/

           $thumb_img->save($Path.'/'.$data,80);
            if (file_exists($imgUrl)) {
                unlink($imgUrl);
            }
            $main_img->opacity(0)->save($destinationPath.'/'.$data,80);
        }//image save
        
        return json_encode($data);
        //return $data;
       // return Redirect::back()->with(array('Image'=>$data));
    }
    public function uploadVideos(Request $request){

        $image = Input::file('file');
        $sourcePath = public_path('doc/product_video');
        $destinationPath = public_path('doc/video');
        $user_img = '';

        $img = str_replace(" ", '-', $image->getClientOriginalName());
        $img = explode('.', $img);
         
        //$videoName = ImageHelper::videoSave($image,$destinationPath,$user_img); //image save
        $videoName = ImageHelper::videoSave($image,$sourcePath,$user_img); //image save
        $videoData = explode('.', $videoName);



        
        if($videoData[1] != 'mp4'){
            exec('ffmpeg -i '.$sourcePath.'/'.$videoName.' -vcodec libx264   -acodec copy '.$destinationPath.'/'.$videoData[0].'.mp4',$output);

            exec('ffmpeg -i '.$sourcePath.'/'.$videoName.' '.$destinationPath.'/ios/'.$videoData[0].'.mp4',$output);

            exec('ffmpeg -i '.$sourcePath.'/'.$videoName.' -strict -2 '.$destinationPath.'/web/'.$videoData[0].'.mp4',$output);;
            $videosName = $videoData[0].'.mp4';

         }else{
            $mainVideo = $sourcePath.'/'.$videoName;
            
            $iosPath = $destinationPath.'/ios';
            $webPath = $destinationPath.'/web';
            File::copy($mainVideo, $destinationPath.'/'.$videoName);
            File::copy($mainVideo, $iosPath.'/'.$videoName);
            File::copy($mainVideo, $webPath.'/'.$videoName);
            $videosName = $videoName;
        }

        if(filesize($destinationPath.'/'.$videosName) <= 1000){
            unlink($sourcePath.'/'.$videoName);
            unlink($destinationPath.'/'.$videosName);
            return "error1";
        }

        $imageData = explode('.', $videoName);
        $newVideoName = $videoName;
        $thumbhnailImage = $imageData[0].'.jpeg';
        $second             = 5;
        $thumbSize       = '512x512';
        $videpath = $sourcePath.'/'.$newVideoName;
        //\Log::debug('--'.$videpath);
        $thumbhnailImagePath = $destinationPath.'/'.$thumbhnailImage;
        //\Log::debug('--'.$thumbhnailImagePath);
        $cmd = "ffmpeg -i {$videpath} -deinterlace -an -ss {$second} -t 00:00:01  -s {$thumbSize} -r 1 -y -vcodec mjpeg -f mjpeg {$thumbhnailImagePath} 2>&1";

        exec($cmd, $output);

        if(substr(end($output),0,20) == 'Output file is empty'){

            unlink($sourcePath.'/'.$videoName);
            unlink($destinationPath.'/'.$videosName);

            return "error";
        }
        return json_encode($videosName);

        //return json_encode($videoData[0].'.mp4');
    }

    public function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public function getProductSerialNumber($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }
        $product = Product::where('product_unit_no',$token)->count();
         if($product > 0){
            $length = 4;
            $this->getProductSerialNumber($length);
        }else{
            return $token;
        }

    }
    public function deleteData(Request $request, $table, $columnName){
        
        DB::table($table)->where($columnName, $request->value)->delete();
        if (!empty($request->name)) {
            $img = $request->path . '/' . $request->name;
            if(isset($request->path1) && !empty($request->path1)){
                $img = $request->path1 . '/' . $request->name;
                if (file_exists($img)) {
                    unlink($img);
                }
            }
            if (file_exists($img)) {
                unlink($img);
            }
            echo true;
        }
        echo false;
    }
    public function getProductReview(Request $request, $table, $column){
        if(!empty($request->value)) {
            $where = [
                [$column, '=', $request->value],
                ['user_id', '=', $request->user_id],
            ];
            $data = DB::table($table)
                ->where($where)
                ->get();
            //echo "<pre>";print_r($data);die;
            return json_encode($data);
        }
    }
}