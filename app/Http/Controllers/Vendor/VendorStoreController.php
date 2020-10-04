<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Store;
use App\StoreCategory;
use App\VendorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\ProductCategory;

class VendorStoreController extends Controller
{

   /* public function __construct()
    {
        $this->middleware("auth:vendor");
    }*/

    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $store = $vendor->store()->first();
        $vendorCategory = ProductCategory::where('parent_category_id', NULL)
        ->where('status', "=","Active")
        ->with('subCategories')->orderBy('order_no', 'asc')->get();
        $data['vendorCategory'] = $vendorCategory;
        $TimeData = array ();
        $storeCategory = array ();
        if ($store)
        {
            $TimeData = DB::table('store_working_time')->where('store_id', '=', $store->id)->get();
            $storeCategory = StoreCategory::where('store_id', '=', $store->id)->pluck('vendor_category_id')->toArray();
        }
        $TimeDetail = array ();
        if (count($TimeData))
        {
            foreach ($TimeData as $val)
            {
                $TimeDetail[$val->day] = [
                    "open_time" => $val->open_time,
                    "closing_time" => $val->closing_time,
                    "is_fullday_open" => $val->is_fullday_open
                ];
            }
        }
        $data['storeCategory'] = $storeCategory;
        $data['working_time'] = $TimeDetail;
        $data['store'] = $store;
        return view('vendor.store.index', $data);
    }

    public function updateStoreDetail(Request $request, Store $store)
    {
        $vendor = Auth::guard('vendor')->user();
        // Validate fields
        $this->validate($request, [
            'store_name' => 'required|unique:stores,store_name,' . $store->id,
            'address' => 'required',
            'category_id' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'store_status' => 'required',
            'store_image' => 'image|mimes:jpeg,png,jpg,svg',
            'banner_image' => 'image|mimes:jpeg,png,jpg,svg',
        ]);

        $storeImage = $request->file('store_image');
        $destinationPath = public_path('doc/store_image');
        $store_image = $request->store_image_1;
        $data = ImageHelper::imageSave($storeImage, $destinationPath, $store_image);
        if ( ! empty($data) && $data != 'false')
        {
            $store->store_image = $data;
        }

        $bannerImage = $request->file('banner_image');
        $destinationPath = public_path('doc/store_banner_image');
        $banner_image = $request->banner_image_1;
        $data = ImageHelper::imageSave($bannerImage, $destinationPath, $banner_image); //image save
        if ( ! empty($data) && $data != 'false')
        {
            $store->banner_image = $data;
            $imgUrl = public_path('doc/store_banner_image/').'/'.$data;
            $Path = public_path('doc/store_banner_images_front');

            $thumb_img = Image::make($imgUrl)->resize(255, 255, function ($constraint) {
            $constraint->aspectRatio();} );
                
            // Fill up the blank spaces with transparent color
            $thumb_img->resizeCanvas(255, 255, 'center', false, array(255, 255, 255, 0));

            $thumb_img = Image::make($imgUrl)->fit(360, 145, function ($constraint) { $constraint->aspectRatio(); } );
            // $thumb_img->save($Path, $data);
            $thumb_img->save($Path.'/'.$data,80);
        }

        $store->fill($request->all());
        $store->store_status = $request->store_status;
        if ($store->save())
        {
            $storeSlug = str_slug($request->store_name, "-");
            DB::table('stores')
                ->where('id', $store->id)
                ->update(['store_slug' => $storeSlug]);
            $storeCategory = new StoreCategory();
            $storeCategoryData = array ();
            DB::table('store_category')->where('store_id', '=', $store->id)->delete();
            foreach ($request->category_id as $category_id)
            {
                $storeCategoryData[] = [
                    "vendor_category_id" => $category_id,
                    "store_id" => $store->id,
                    "added_by_user_id" => $vendor->id
                ];
            }
            $storeCategory->insert($storeCategoryData);
            $TimeData = array ();
            $TimeDetail = $request->time;
            if (count($TimeDetail))
            {
                DB::table('store_working_time')->where('store_id', '=', $store->id)->delete();
                foreach ($TimeDetail as $key => $val)
                {
                    if (isset($val['is_fullday_open']))
                    {
                        $TimeData[] = [
                            "store_id" => $store->id,
                            "day" => $key,
                            "is_fullday_open" => "Yes",
                            "open_time" => null,
                            "closing_time" => null,
                        ];
                    }
                    else
                    {
                        $TimeData[] = [
                            "store_id" => $store->id,
                            "day" => $key,
                            "is_fullday_open" => "No",
                            "open_time" => date("H:i:s", strtotime($val['open_time'])),
                            "closing_time" => date("H:i:s", strtotime($val['closing_time'])),
                        ];
                    }
                }
            }
            DB::table('store_working_time')->insert($TimeData);
            return redirect(url("vendor/store"))->with('success', trans('messages.store.updated'));
        }

        return redirect(url("vendor/store"))->with('error', trans('messages.error'));
    }

}
