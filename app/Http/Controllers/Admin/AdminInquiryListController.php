<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\UserContactUs;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AdminInquiryListController extends Controller
{

    public function index(){
        return view('admin.inquiryList.inquiry_list');
    }

    /**
     * Search inquiry_list .
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

            $query = UserContactUs::select('email','subject','id');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterSubscribe($request->search['value'], $query);

            $inquiries = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($inquiries));

            $data->recordsFiltered = $data->recordsTotal = $data->total;
            foreach ($data->data as $inquiry) {

                 $inquiry->action = 
                    '<a href="'.url(route('inquiry.view', ['inquiry' => $inquiry->id ])).'" title="View"><i class="la la-eye"></i></a>';
                
                }
            return response()->json($data);
        }

    }

    /**
    *
    * inquiry view 
    * @param $inquiry
    * @return $inquiry
    *
    **/
    public function show(UserContactUs $inquiry)
    {
        return view('admin.inquiryList.inquiry_view',[

            'inquiry' => $inquiry,
        ]);

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
            ->orWhere('subject', 'like', '%'.$search.'%');
    }
}