<?php
/**
 * Created by PhpStorm.
 * User: ashwin
 * Date: 11/1/18
 * Time: 3:10 PM
 */

namespace App\Http\Controllers\Admin;


use App\Admin;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductAttrCombination;
use App\ProductShipping;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    private $validationRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed',
        'profile_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
    ];

    /**
     * Display Admin details.
     *
     * @return json
     */
    public function index()
    {
        return view('admin.profile.admin_list');
    }

    /**
     * Search Admin.
     *
     * @return json
     */
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $url = request()->segment(1);
            $loginUser = Auth::guard($url)->user();
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $query = Admin::select('id', 'first_name', 'last_name', 'email', 'mobile_no', 'status');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterAdmin($request->search['value'], $query);

            $admin = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($admin));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $admins) {

                $admins->action = '<a href="' . url(route('adminEdit', ['admin' => $admins->id])) . '" title="Edit"><i class="la la-edit"></i></a>' .
                    '<a href="' . url(route('adminProfile', ['admin' => $admins->id])) . '" title="View"><i class="la la-eye"></i></a>' .
                    '<a class="delete-data" data-name="admin" href="' . url(route('deleteAdmin', ['admin' => $admins->id])) . '" title="Delete"><i class="la la-trash"></i></a>';
                if ($loginUser->id === $admins->id) {
                    $admins->status = $admins->status ? '<a class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                        : '<a class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
                } else {
                    $admins->status = $admins->status ? '<a href="' . url(route('changeAdminStatus', ['admin' => $admins->id])) . '" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                        : '<a href="' . url(route('changeAdminStatus', ['admin' => $admins->id])) . '" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
                }
            }

            return response()->json($data);
        }

    }

    /**
     * Gets all Admin.
     *
     * @return json
     */
    public function create()
    {
        // $data['contactTypes'] = ContactType::all();
        return view('admin.profile.admin_create');
    }

    /**
     * Save the Admin.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        // Validate fields
        $this->validate($request, $this->validationRules);

        $admin = new Admin();

        $admin->fill($request->all());
        $image = $request->file('profile_image');
        $destinationPath = public_path('doc/profile_image');
        $user_img = $request->profile_image_1;
        $data = ImageHelper::imageSave($image, $destinationPath, $user_img); //image save
        if (!empty($data) && $data != 'false') {
            $admin->profile_image = $data;
        }
        $admin->status = 1;
        $admin->type = 'admin';
        $admin->password = bcrypt($request->password);

        if ($admin->save()) {
            $nameSlug = str_slug($request->first_name . "-" . $request->last_name . "-" . $admin->id, "-");
            DB::table('users')
                ->where('id', $admin->id)
                ->update(['name_slug' => $nameSlug]);
            return redirect(route('adminList'))->with('success', trans('messages.admin.added'));
        }
        return redirect(route('adminCreate'))->with('error', trans('messages.error'));
    }


    /**
     * Change status of the vendors.
     *
     * @param Admin $vendor
     * @return json
     */
    public function changeStatus(Admin $admin)
    {
        // echo "<pre>";print_r($vendor);die;
        $admin->status = !$admin->status;

        if ($admin->save()) {

            return redirect(route('adminList'))->with('success', trans('messages.admin.change_status'));
        }

        return redirect(route('adminList'))->with('error', trans('messages.error'));
    }

    /**
     * Show Admin view page.
     *
     * @param Admin $users
     * @return json
     */
    public function profile(Admin $admin)
    {

        // echo "hi";die;
        return view('admin.profile.profile', [
            'admin' => $admin
        ]);
    }

    /**
     * Show Admin edit page.
     *
     * @param Admin $users
     * @return json
     */
    public function edit(Admin $admin)
    {

        // echo "hi";die;
        return view('admin.profile.admin_create', [
            'admins' => $admin
        ]);
    }

    /**
     * Update the vendor.
     *
     * @param Request $request
     * @param int $vendorId
     * @return json
     */
    public function update(Request $request, Admin $admin)
    {
        $this->validationRules['email'] = 'unique:users,email,' . $admin->id . '|email';
        unset($this->validationRules['password']);
        $this->validate($request, $this->validationRules);

        $image = $request->file('profile_image');
        $destinationPath = public_path('doc/profile_image');
        $user_img = $request->profile_image_1;
        $data = ImageHelper::imageSave($image, $destinationPath, $user_img); //image save
        if (!empty($data) && $data != 'false') {
            $admin->profile_image = $data;
        }

        $response = [
            'status' => 0,
            'message' => trans('messages.failed'),
        ];

        $admin->fill($request->all());

        if ($admin->save()) {
            $nameSlug = str_slug($request->first_name . "-" . $request->last_name . "-" . $admin->id, "-");
            DB::table('users')
                ->where('id', $admin->id)
                ->update(['name_slug' => $nameSlug]);
            return redirect(route('adminList'))->with('success', trans('messages.admin.updated'));
        }

        return redirect(route('adminList'))->with('error', trans('messages.error'));
    }

    /**
     * Delete Admin by unique identifier.
     *
     * @return json
     */
    public function destroy(Admin $admin)
    {
        /*ProductShipping::where('added_by_user_id', $admin->id)->delete();
        Product::where('added_by_user_id', $admin->id)->delete();
        */
        if ($admin->delete()) {

            return redirect(route('adminList'))->with('success', trans('messages.admin.deleted'));
        }

        return redirect(route('adminList'))->with('error', trans('messages.error'));
    }

    /**
     * Filter Admin listing.
     *
     * @param $search
     * @return $query
     */
    private function filterAdmin($search, $query)
    {
        $query->where('first_name', 'like', '%' . $search . '%')
            ->orWhere('last_name', 'like', '%' . $search . '%')
            ->orWhere('mobile_no', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%');
    }
}