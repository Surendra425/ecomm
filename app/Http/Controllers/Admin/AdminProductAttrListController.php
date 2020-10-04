<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 17/1/18
 * Time: 12:17 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\ProductAttributeList;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AdminProductAttrListController extends Controller
{
    private $validationRules = [

        'attribute_name' => 'required|unique:product_attributes_list,attribute_name',
    ];


    /**
     * Display Attribute List details.
     *
     * @return json
     */
    public function index()
    {

        return view('admin.attribute.attr_list');
    }

    /* Search ProductAttributeList.
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

            $query = ProductAttributeList::select('attribute_name','status','id');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterAttr($request->search['value'], $query);

            $attribute = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($attribute));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $attributes) {
                $attributes->action = '<a href="#edit_model" data-id="'.$attributes->id.'" title="Edit" data-toggle="modal" data-target="#edit_model"><i class="la la-edit"></i></a>'.
                    '<a class="delete-data" data-name="attribute" href="'.url(route('deleteAttr', ['attribute' => $attributes->id ])).'" title="Delete"><i class="la la-trash"></i></a>';

                $attributes->status = ($attributes->status === 'Active') ? '<a href="'.url(route('changeStatus', ['collection' => $attributes->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('changeStatus', ['collection' => $attributes->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }
    /**
     * Filter ProductAttributeList listing.
     *
     * @param $search
     * @return $query
     */
    private function filterAttr($search, $query)
    {
        $query->where('attribute_name', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->orWhere('id', 'like', '%'.$search.'%');
    }

    /**
     * Save the ProductAttributeList.
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $attribute = new ProductAttributeList();
        $attribute->fill($request->all());
        $attribute->attribute_name = ucfirst($request->attribute_name);
        if ($attribute->save()) {

            return redirect(route('attributes.index'))->with('success',trans('messages.attribute.added'));
        }

        return redirect(route('attributes.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the country.
     *
     * @param ProductAttributeList $attribute
     * @return json
     */
    public function changeStatus(ProductAttributeList $attribute)
    {
        // echo "<pre>";print_r($vendor);die;
        if($attribute->status == 'Active'){
            $attribute->status ='Inactive';
        }else{
            $attribute->status ='Active';
        }

        if($attribute->save()) {

            return redirect(route('attributes.index'))->with('success', trans('messages.attribute.change_status'));
        }

        return redirect(route('attributes.index'))->with('error', trans('messages.error'));
    }


    /**
     * Show ProductAttributeList edit page.
     *
     * @param ProductAttributeList $attribute
     * @return json
     */
    public function edit(ProductAttributeList $attribute)
    {
        echo json_encode($attribute);
    }

    /**
     * Update the attribute.
     *
     * @param Request $request
     * @param int $attribute
     * @return json
     */
    public function update(Request $request, ProductAttributeList $attribute)
    {
        //echo $store->id;die;
        //echo "hi";die;
        // Validate fields
        unset($this->validationRules['attribute_name']);
        $this->validationRules['edit_attribute_name'] = 'required|unique:product_attributes_list,attribute_name,'.$attribute->id;
        $this->validate($request, $this->validationRules);

        $attribute->fill($request->all());
        $attribute->attribute_name = ucfirst($request->edit_attribute_name);
        if ($attribute->save()) {

            return redirect(route('attributes.index'))->with('success', trans('messages.attribute.updated'));
        }

        return redirect(route('attributes.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete attribute by unique identifier.
     *
     * @return json
     */
    public function destroy(ProductAttributeList $attribute)
    {
        if($attribute->delete()) {

            return redirect(route('attributes.index'))->with('success', trans('messages.attribute.deleted'));
        }

        return redirect(route('attributes.index'))->with('error', trans('messages.error'));
    }

    /**
     * check attribute name unique.
     *
     * @return json
     */
    public function checkAttrName(Request $request){
        $attrId = $request->attr_id;
         if(isset($attrId) && !empty($attrId)){
             $data = ProductAttributeList::where('attribute_name',$request->attrName)->where('id','!=',$attrId)->get();
             if(count($data) > 0){
                 $responce = 1;
             }else{
                 $responce = 0;
             }
         }else{
             $data = ProductAttributeList::where('attribute_name',$request->attrName)->get();
             if(count($data) > 0){
                 $responce = 1;
             }else{
                 $responce = 0;
             }
         }
        echo $responce;
    }

}