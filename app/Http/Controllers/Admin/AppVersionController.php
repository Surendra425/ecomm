<?php

namespace App\Http\Controllers\Admin;

use App\AppUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AppVersions;
use App\Helpers\ImageHelper;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class AppVersionController extends Controller
{

    /*private $validationRules = [
        'advertisement_name' => 'required|unique:advertisements,advertisement_name',
        'advertisement_tagline' => 'required|unique:advertisements,advertisement_tagline',
        'background_image' => 'image|mimes:jpeg,png,jpg,svg',
    ];*/

    public function index()
    {
        return view('admin.version.index');
    }

    

    /**
     * Display create app version page.
     *
     * @return json
     */
    public function create()
    {
        return view('admin.version.create');
    }

    /**
    store app version details
    */
    public function store(Request $request){
        $app_versions = new AppVersions();
        $versionInfo = [];
        if(empty($request->app_type_ios) && empty($request->app_type_android)){
            return redirect(route('versions.index'))->with('error', trans('messages.error'));
        }else
        {
            if(!empty($request->app_type_ios))
            {
              $versionInfo[] = array(
                'app_version'=>$request->ios_version,
                'app_url'=>$request->ios_app_url,
                'app_is_update'=>$request->ios_update_type,
                'app_update_msg'=>$request->ios_update_msg,
                'app_maintenance_msg'=>$request->ios_maintenanace_msg,
                'app_type'=>$request->app_type_ios,
              );
            }
            if(!empty($request->app_type_android)){
            $versionInfo[] = array(
                        'app_version'=>$request->android_version,
                        'app_url'=>$request->android_app_url,
                        'app_is_update'=>$request->android_update_type,
                        'app_update_msg'=>$request->android_update_msg,
                        'app_maintenance_msg'=>$request->android_maintenanace_msg,
                        'app_type'=>$request->app_type_android,
                      );
            }

            if(!empty($versionInfo))
            {
                AppUser::where('app_type' , $versionInfo['app_type'])->update(['is_show_update' => 0]);

            }

            $data = $app_versions->insert($versionInfo);
            if($data){
                return redirect(route('versions.index'))->with('success',trans('messages.versions.added'));
            }else{
                return redirect(route('versions.index'))->with('error', trans('messages.error'));
            }
        }
        
        //dd($versionInfo);
    }
 /**
     * Search app version listing.
     
     */

    public function search(Request $request)
    {
        //dd($request);die;
        if($request->app_type == 1){
            $type = "iphone";
        }else{
            $type = "android";
        }
        if($request->ajax()) {
            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $query = AppVersions::select('app_version','app_url','app_update_msg','app_maintenance_msg','app_type','id',
                DB::raw('(CASE WHEN app_is_update = "1" THEN "Live & no updates avilable" 
                    WHEN app_is_update = "2" THEN "Optional update avilable" 
                    WHEN app_is_update = "3" THEN "Compulsory update avilable"
                    ELSE "Under Construction" END) AS app_is_update')
            )->where('app_type',$request->app_type)->orderBy('id','DSCE');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterVersion($request->search['value'], $query);

            $version = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($version));
//dd($data);die;
            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $key=>$versions) {
                $versions->action='';
                if($key == 0){
                    $versions->action = '<a href="'.url(route('versionsEdit', ['version' => $versions->id ,'type'=>$type])).'" title="Edit"><i class="la la-edit"></i></a>';
                }
                
            }

            return response()->json($data);
        }
    }
    /**
     * Filter app version listing.
     *
     * @param
     *            $search
     * @return $query
     */
    private function filterVersion($search, $query)
    {
        $query->where(function ($query) use ($search) {
            
            $query->where('app_version', 'like', '%' . $search . '%')
                ->orWhere('app_url', 'like', '%' . $search . '%')
                ->orWhere('app_is_update', 'like', '%' . $search . '%')
                ->orWhere('app_update_msg', 'like', '%' . $search . '%')
                ->orWhere('app_maintenance_msg', 'like', '%' . $search . '%');
        });
    }

    /*
    * version edit
    * @param $version , $type
    * @return json
    */
    public function edit(AppVersions $version,$type)
    {
        $data['type'] = $type;
        $data['version'] = $version;
        return view('admin.version.edit', $data);
    }

    public function update(Request $request,AppVersions $version){

        $isChange = 0;
        if($version->app_version != $request->app_version)
        {
            $isChange = 1;
        }
            $version->fill($request->all());
        if ($version->save())
        {
            if($isChange)
            {
                AppUser::where('app_type' , $request->app_type)->update(['is_show_update' => 0]);
            }
            return redirect(route('versions.index'))->with('success', trans('messages.versions.updated'));
        }

        return redirect(route('versions.index'))->with('error', trans('messages.error'));
    }
}
