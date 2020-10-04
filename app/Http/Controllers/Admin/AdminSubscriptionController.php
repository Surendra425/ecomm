<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 27/2/18
 * Time: 5:35 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Subscribers;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AdminSubscriptionController extends Controller
{

    public function index(){
        return view('admin.subscribe.sbscribe_list');
    }

    /**
     * Search Subscribe User.
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

            $query = Subscribers::select('email','user_id','id');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterSubscribe($request->search['value'], $query);

            $subscribe = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($subscribe));

            $data->recordsFiltered = $data->recordsTotal = $data->total;
            foreach ($data->data as $subscribers) {
                if(!empty($subscribers->user_id)){
                    $subscribers->user_id = '<a href="'.url(route('vieUser', ['user' => $subscribers->user_id ])).'" title="View">'.$subscribers->user_id.'</a>';
                }else{
                    $subscribers->user_id = $subscribers->user_id;
                }
                }
            return response()->json($data);
        }

    }
    /**
     * Filter City listing.
     *
     * @param $search
     * @return $query
     */
    private function filterSubscribe($search, $query)
    {
        $query->where('email', 'like', '%'.$search.'%')
            ->orWhere('user_id', 'like', '%'.$search.'%');
    }
}