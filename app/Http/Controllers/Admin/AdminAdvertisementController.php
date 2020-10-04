<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Advertisement;
use App\Helpers\ImageHelper;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class AdminAdvertisementController extends Controller
{

    private $validationRules = [
        'advertisement_name' => 'required|unique:advertisements,advertisement_name',
        'advertisement_tagline' => 'required|unique:advertisements,advertisement_tagline',
        'background_image' => 'image|mimes:jpeg,png,jpg,svg',
    ];

    public function index()
    {
        return view('admin.advertisements.advertisement_list');
    }

    /**
     * Search Advertisement.
     *
     * @return json
     */
    public function search(Request $request)
    {
        if ($request->ajax())
        {
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = Advertisement::select('id', 'advertisement_name', 'advertisement_tagline', 'background_image', 'start_at', 'end_at', 'status', 'display_status');
            
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterAdvertisement($request->search['value'], $query);

            $advertisement = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($advertisement));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $advertisements)
            {
                $advertisements->action = '<a href="' . url(route('advertisement.edit', ['advertisement' => $advertisements->id])) . '" title="Edit"><i class="la la-edit"></i></a>' .
                        '<a href="' . url(route('profileAdvertisement', ['advertisement' => $advertisements->id])) . '" title="View"><i class="la la-eye"></i></a>' .
                        '<a class="delete-data" data-name="advertisement" href="' . url(route('deleteAdvertisement', ['advertisement' => $advertisements->id])) . '" title="Delete"><i class="la la-trash"></i></a>';

                $advertisements->display_status = ($advertisements->display_status === 'Yes') ? '<a href="' . url(route('changeAdvertisementDisplayStatus', ['advertisement' => $advertisements->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Yes</a>' : '<a href="' . url(route('changeAdvertisementDisplayStatus', ['advertisement' => $advertisements->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">No</a>';

                $advertisements->status = ($advertisements->status === 'Active') ? '<a href="' . url(route('changeAdvertisementStatus', ['advertisement' => $advertisements->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>' : '<a href="' . url(route('changeAdvertisementStatus', ['advertisement' => $advertisements->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }

    /**
     * Filter Advertisement listing.
     *
     * @param $search
     * @return $query
     */
    private function filterAdvertisement($search, $query)
    {
        $query->where('advertisement_name', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('advertisement_tagline', 'like', '%' . $search . '%')
                ->orWhere('display_status', 'like', '%' . $search . '%');
    }

    /**
     * Display create Advertisement page.
     *
     * @return json
     */
    public function create()
    {
        return view('admin.advertisements.advertisement_create');
    }

    /**
     * Save the Advertisement.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $advertisement = new Advertisement();
        $advertisement->fill($request->all());
        $advertisement->advertisement_name = ucfirst($request->advertisement_name);
        $advertisement->advertisement_tagline = ucfirst($request->advertisement_tagline);
        $dateRange = $request->daterange;

        //echo "<pre>"; print_r($dateRange);die;
        if (strpos($dateRange, ' / ') !== false)
        {
            $dateRange = explode(' / ', $dateRange);
        }
        else
        {
            $dateRange = explode(' - ', $dateRange);
        }

        $advertisement->start_at = date('Y-m-d', strtotime($dateRange[0]));
        $advertisement->end_at = date('Y-m-d', strtotime($dateRange[1]));
        //advertisement image save
        $image = $request->file('background_image');
        //dd($image);die;
        $destinationPath = public_path('doc/advertisement_image');
        $user_img = $request->background_image_1;
        $data = ImageHelper::imageSave($image, $destinationPath, $user_img); //image save
        if ( ! empty($data) && $data != 'false')
        {
            $advertisement->background_image = $data;
        }

        if ($advertisement->save())
        {
            $advertisementSlug = str_slug($request->advertisement_name.'-'.$advertisement->id, "-");
            DB::table('advertisements')
                ->where('id', $advertisement->id)
                ->update(['advertisement_slug' => $advertisementSlug]);
            return redirect(route('advertisement.index'))->with('success', trans('messages.advertisement.added'));
        }

        return redirect(route('advertisement.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the advertisement.
     *
     * @param Advertisement $advertisement
     * @return json
     */
    public function changeStatus(Advertisement $advertisement)
    {
        // echo "<pre>";print_r($vendor);die;
        if ($advertisement->status == 'Active')
        {
            $advertisement->status = 'Inactive';
        }
        else
        {
            $advertisement->status = 'Active';
        }

        if ($advertisement->save())
        {

            return redirect(route('advertisement.index'))->with('success', trans('messages.advertisement.change_status'));
        }

        return redirect(route('advertisement.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the advertisements.
     *
     * @param Advertisement $advertisement
     * @return json
     */
    public function changeDisplayStatus(Advertisement $advertisement)
    {
        // echo "<pre>";print_r($vendor);die;
        if ($advertisement->display_status == 'Yes')
        {
            $advertisement->display_status = 'No';
        }
        else
        {
            $advertisement->display_status = 'Yes';
        }

        if ($advertisement->save())
        {

            return redirect(route('advertisement.index'))->with('success', trans('messages.advertisement.change_display_status'));
        }

        return redirect(route('advertisement.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show advertisement view page.
     *
     * @param Advertisement $advertisement
     * @return json
     */
    public function profile(Advertisement $advertisement)
    {
        return view('admin.advertisements.profile', [
            'advertisement' => $advertisement
        ]);
    }

    /**
     * Show Advertisements edit page.
     *
     * @param Advertisement $advertisement
     * @return json
     */
    public function edit(Advertisement $advertisement)
    {
        //  echo "<pre>";print_r($advertisement);die;
        return view('admin.advertisements.advertisement_create', [
            'advertisement' => $advertisement
        ]);
    }

    /**
     * Update the Advertisement.
     *
     * @param Request $request
     * @param int $advertisement
     * @return json
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        $this->validationRules['advertisement_name'] = 'required|unique:advertisements,advertisement_name,' . $advertisement->id;
        $this->validationRules['advertisement_tagline'] = 'required|unique:advertisements,advertisement_tagline,' . $advertisement->id;
        $this->validate($request, $this->validationRules);

        $advertisement->fill($request->all());
        $advertisement->advertisement_tagline = ucfirst($request->advertisement_tagline);
        $advertisement->advertisement_name = ucfirst($request->advertisement_name);
        $dateRange = $request->daterange;

        //echo "<pre>"; print_r($dateRange);die;
        if (strpos($dateRange, ' / ') !== false)
        {
            $dateRange = explode(' / ', $dateRange);
        }
        else
        {
            $dateRange = explode(' - ', $dateRange);
        }

        $advertisement->start_at = date('Y-m-d', strtotime($dateRange[0]));
        $advertisement->end_at = date('Y-m-d', strtotime($dateRange[1]));

        //advertisement image save
        $image = $request->file('background_image');
        $destinationPath = public_path('doc/advertisement_image');
        $user_img = $request->background_image_1;
        $data = ImageHelper::imageSave($image, $destinationPath, $user_img); //image save
        if ( ! empty($data) && $data != 'false')
        {
            $advertisement->background_image = $data;
        }

        if ($advertisement->save())
        {
            $advertisementSlug = str_slug($request->advertisement_name.'-'.$advertisement->id, "-");
            DB::table('advertisements')
                ->where('id', $advertisement->id)
                ->update(['advertisement_slug' => $advertisementSlug]);
            return redirect(route('advertisement.index'))->with('success', trans('messages.advertisement.updated'));
        }

        return redirect(route('advertisement.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete advertisements by unique identifier.
     *
     * @return json
     */
    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->delete())
        {

            return redirect(route('advertisement.index'))->with('success', trans('messages.advertisement.deleted'));
        }

        return redirect(route('advertisement.index'))->with('error', trans('messages.error'));
    }

}
