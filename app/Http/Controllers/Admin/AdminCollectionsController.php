<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 13/1/18
 * Time: 2:48 PM
 */

namespace App\Http\Controllers\Admin;


use App\Collections;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class AdminCollectionsController extends Controller
{

    private $validationRules = [

        'collection_name' => 'required|unique:collections,collection_name',
        'collection_tagline' => 'required|unique:collections,collection_tagline',
        'background_image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
    ];


    /**
     * Display Collection details.
     *
     * @return json
     */
    public function index()
    {

        return view('admin.collections.collection_list');
    }

    /**
     * Search Collection.
     *
     * @return json
     */
    public function search(Request $request)
    {
        if($request->ajax()) {
            $currentPage = ($request->start == 0) ? 1: (($request->start/$request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $query = Collections::select('collection_name','collection_tagline','status','id','display_status');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCollection($request->search['value'], $query);

            $collection = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($collection));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $collections) {
                $collections->action = '<a href="'.url(route('collections.edit', ['collection' => $collections->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a href="'.url(route('profileCollection', ['collection' => $collections->id ])).'" title="View"><i class="la la-eye"></i></a>'.
                    '<a class="delete-data" data-name="collection" href="'.url(route('deleteCollection', ['collection' => $collections->id ])).'" title="Delete"><i class="la la-trash"></i></a>';

                $collections->display_status = ($collections->display_status === 'Yes') ? '<a href="'.url(route('changeCollectionDisplayStatus', ['collection' => $collections->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Yes</a>'
                    : '<a href="'.url(route('changeCollectionDisplayStatus', ['collection' => $collections->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">No</a>';

                $collections->status = ($collections->status === 'Active') ? '<a href="'.url(route('changeCollectionStatus', ['collection' => $collections->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('changeCollectionStatus', ['collection' => $collections->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }
   /**
     * Filter Collection listing.
     *
     * @param $search
     * @return $query
     */
    private function filterCollection($search, $query)
    {
        $query->where('collection_name', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->orWhere('collection_tagline', 'like', '%'.$search.'%')
            ->orWhere('display_status', 'like', '%'.$search.'%');
    }

    /**
     * Display create Collection page.
     *
     * @return json
     */
    public function create()
    {
        return view('admin.collections.collection_create');
    }

    /**
     * Save the Collection.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $collection = new Collections();
        $collection->fill($request->all());
        $collection->collection_name = ucfirst($request->collection_name);
        $collection->collection_tagline = ucfirst($request->collection_tagline);
        //collection image save
        $image = $request->file('background_image');
        $img = getimagesize($image);
        //dd($image);die;
        $destinationPath = public_path('doc/collection_image');
        $user_img = $request->background_image_1;
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img); //image save
        if (!empty($data) && $data != 'false') {
            $collection->background_image = $data;

            $imgUrl = public_path('doc/collection_image/').'/'.$data;
            $Path = public_path('doc/collection_image_temp');
            if($img[0] < 200 || $img[1] < 200 ){
                $thumb_img = Image::make($imgUrl)->resizeCanvas(263, 200, 'center', false, array(214, 218, 223, 0));
                $main_img = Image::make($imgUrl)->resizeCanvas(1200, 700, 'center', false, array(214, 218, 223, 0));
            }else{
                $thumb_img = Image::make($imgUrl)->resize(263, 200, function ($constraint) {
                    $constraint->aspectRatio();} );
                $main_img = Image::make($imgUrl)->resize(1200, 700, function ($constraint) {
                    $constraint->aspectRatio();} );

                // Fill up the blank spaces with transparent color
                //$thumb_img->resizeCanvas(263, 200, 'center', false, array(214, 218, 223, 0));
                    $thumb_img = Image::make($imgUrl)->fit(263, 153, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();} );
                $main_img->resizeCanvas(1200, 700, 'center', false, array(214, 218, 223, 0));
            }
            $thumb_img->save($Path.'/'.$data,80);
            if (file_exists($imgUrl)) {
                unlink($imgUrl);
            }
            $main_img->save($destinationPath.'/'.$data,80);
        }

        if ($collection->save()) {
            $collectionSlug = str_slug($request->collection_name.'-'.$collection->id, "-");
            DB::table('collections')
                ->where('id', $collection->id)
                ->update(['collection_slug' => $collectionSlug]);
            return redirect(route('collections.index'))->with('success',trans('messages.collection.added'));
        }

        return redirect(route('collections.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the collection.
     *
     * @param Collections $collection
     * @return json
     */
    public function changeStatus(Collections $collection)
    {
        // echo "<pre>";print_r($vendor);die;
        if($collection->status == 'Active'){
            $collection->status ='Inactive';
        }else{
            $collection->status ='Active';
        }

        if($collection->save()) {

            return redirect(route('collections.index'))->with('success', trans('messages.collection.change_status'));
        }

        return redirect(route('collections.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the collections.
     *
     * @param Collections $collection
     * @return json
     */
    public function changeDisplayStatus(Collections $collection)
    {
        // echo "<pre>";print_r($vendor);die;
        if($collection->display_status == 'Yes'){
            $collection->display_status ='No';
        }else{
            $collection->display_status ='Yes';
        }

        if($collection->save()) {

            return redirect(route('collections.index'))->with('success', trans('messages.collection.change_display_status'));
        }

        return redirect(route('collections.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show collection view page.
     *
     * @param Collections $collection
     * @return json
     */
    public function profile(Collections $collection)
    {
        return view('admin.collections.profile', [
            'collection' => $collection
        ]);
    }

    /**
     * Show Collections edit page.
     *
     * @param Collections $collection
     * @return json
     */
    public function edit(Collections $collection)
    {
        //  echo "<pre>";print_r($collection);die;
        return view('admin.collections.collection_create', [
            'collection' =>$collection
        ]);
    }

    /**
     * Update the Collection.
     *
     * @param Request $request
     * @param int $collection
     * @return json
     */
    public function update(Request $request, Collections $collection)
    {
        //echo $store->id;die;
        //echo "hi";die;
        // Validate fields

        $this->validationRules['collection_name'] = 'required|unique:collections,collection_name,'.$collection->id;
        $this->validationRules['collection_tagline'] = 'required|unique:collections,collection_tagline,'.$collection->id;
        $this->validationRules['background_image'] = 'image|mimes:jpeg,png,jpg,svg|max:2048';
        
        $this->validate($request, $this->validationRules);

        $collection->fill($request->all());
        $collection->collection_tagline = ucfirst($request->collection_tagline);
        $collection->collection_name = ucfirst($request->collection_name);
       // dd($request->file('background_image'));die;
        if(!empty($request->file('background_image'))){
           $image = $request->file('background_image');
        $img = getimagesize($image);
        $destinationPath = public_path('doc/collection_image');
        $user_img = $request->background_image_1;
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img); //image save
        if (!empty($data) && $data != 'false') {
            $collection->background_image = $data;

            $imgUrl = public_path('doc/collection_image/').'/'.$data;
            $Path = public_path('doc/collection_image_temp');
            if($img[0] < 200 || $img[1] < 200 ){
            $thumb_img = Image::make($imgUrl)->resizeCanvas(263, 153, 'center', false, array(214, 218, 223, 0));
            $main_img = Image::make($imgUrl)->resizeCanvas(1200, 700, 'center', false, array(214, 218, 223, 0));
        }else{
                $thumb_img = Image::make($imgUrl)->resize(263, 153, function ($constraint) {
                    $constraint->aspectRatio();} );
                $main_img = Image::make($imgUrl)->resize(1200, 700, function ($constraint) {
                    $constraint->aspectRatio();} );

                // Fill up the blank spaces with transparent color
                //$thumb_img->resizeCanvas(263, 153, 'center', false, array(214, 218, 223, 0));
                    $thumb_img = Image::make($imgUrl)->fit(263, 153, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();} );
                $main_img->resizeCanvas(1200, 700, 'center', false, array(214, 218, 223, 0));
            }
            $thumb_img->save($Path.'/'.$data,80);
            if (file_exists($imgUrl)) {
                unlink($imgUrl);
            }
            $main_img->save($destinationPath.'/'.$data,80);
        } 
        }
        //collection image save
        
        if ($collection->save()) {
            $collectionSlug = str_slug($request->collection_name.'-'.$collection->id, "-");
            DB::table('collections')
                ->where('id', $collection->id)
                ->update(['collection_slug' => $collectionSlug]);
            return redirect(route('collections.index'))->with('success', trans('messages.collection.updated'));
        }

        return redirect(route('collections.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete collections by unique identifier.
     *
     * @return json
     */
    public function destroy(Collections $collection)
    {
        if($collection->delete()) {

            return redirect(route('collections.index'))->with('success', trans('messages.collection.deleted'));
        }

        return redirect(route('collections.index'))->with('error', trans('messages.error'));
    }

}