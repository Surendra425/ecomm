<?php

namespace App\Http\Controllers\Admin;

use App\EmailUsers;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Mail\WelcomeMail;
use App\User;
use App\Subscribers;
use App\ProductCart;
use App\ProductVisitor;
use App\ProductLike;
use App\StoreFollower;
use App\StoreVisitor;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminUserController extends Controller
{
    private $validationRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed',
        'profile_image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
    ];
    
    /**
     * Display Customer details.
     *
     * @return json
     */
    public function index()
    {
        return view('admin.users.users_list');
    }

    /**
     * Search Customers.
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

            $query = Customer::select('id', 'first_name',  'last_name', 'email','mobile_no', 'status');

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCustomers($request->search['value'], $query);

            $customer = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($customer));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $customers) {

                $customers->action = '<a href="'.url(route('users.edit', ['user' => $customers->id ])).'" title="Edit"><i class="la la-edit"></i></a>'.
                    '<a href="'.url(route('vieUser', ['user' => $customers->id ])).'" title="View"><i class="la la-eye"></i></a>';
                    /*'<a class="delete-data" data-name="customer" href="'.url(route('deleteUser', ['user' => $customers->id ])).'" title="Delete"><i class="la la-trash"></i></a>'*/

                $customers->status = $customers->status ? '<a href="'.url(route('changeUserStatus', ['user' => $customers->id ])).'" class="m-badge m-badge--success m-badge--wide" title="Active">Active</a>'
                    : '<a href="'.url(route('changeUserStatus', ['user' => $customers->id ])).'" class="m-badge m-badge--danger m-badge--wide" title="Inactive">Inactive</a>';
            }

            return response()->json($data);
        }
    }
    
    /**
     * Gets all customers.
     *
     * @return json
     */
    public function create()
    {
        // $data['contactTypes'] = ContactType::all();
        return view('admin.users.user_create');
    }

    /**
     * Save the customer.
     *
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        // Validate fields
        $this->validate($request, $this->validationRules);

        $customer = new Customer();
        $customer->fill($request->all());
        $customer->status = 1;
        $customer->password = bcrypt($request->password);
        $image = $request->file('profile_image');
        $destinationPath = public_path('doc/profile_image');
        $user_img = $request->profile_image_1;
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img); //image save
        if (!empty($data) && $data != 'false') {
            $customer->profile_image = $data;
        }
        if ($customer->save()) {
            $nameSlug = str_slug($request->first_name ."-". $request->last_name."-".$customer->id, "-");
            DB::table('users')
                ->where('id', $customer->id)
                ->update(['name_slug' => $nameSlug]);
            try
            {
                Mail::to($customer->email)->send(new WelcomeMail($customer));
            }
            catch (Exception $exc)
            {
//                echo $exc->getTraceAsString();
            }
            return redirect(route('users.index'))->with('success', trans('messages.users.added'));
        }

        return redirect(route('users.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the users.
     * 
     * @param Customer $user
     * @return json
     */
    public function changeStatus(Customer $user)
    {
        $user->status = !$user->status;

        if($user->save()) {

            return redirect(route('users.index'))->with('success', trans('messages.users.change_status'));
        }

        return redirect(route('users.index'))->with('error', trans('messages.error'));
    }
    
    /**
     * Show customer edit page.
     * 
     * @param Customer $users
     * @return json
     */
    public function edit(Customer $user)
    {
        return view('admin.users.user_create', [
            'user' => $user
        ]);
    }

    /**
     * Upate the customer.
     *
     * @param Request $request
     * @param int $customerId
     * @return json
     */
    public function update(Request $request, Customer $user)
    {
        $this->validationRules['email'] = 'unique:users,email,'.$user->id.'|email';
        unset($this->validationRules['password']);
        $this->validate($request, $this->validationRules);
        
        $response = [
            'status' => 0,
            'message' => trans('messages.failed'),
        ];
        $image = $request->file('profile_image');
        $destinationPath = public_path('doc/profile_image');
        $user_img = $request->profile_image_1;
        $data = ImageHelper::imageSave($image,$destinationPath,$user_img); //image save
        if (!empty($data) && $data != 'false') {
            $user->profile_image = $data;
        }
            $user->fill($request->all());

            if ($user->save()) {
                $nameSlug = str_slug($request->first_name ."-". $request->last_name."-".$user->id, "-");
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['name_slug' => $nameSlug]);
                return redirect(route('users.index'))->with('success', trans('messages.users.updated'));
            }

            return redirect(route('users.index'))->with('error', trans('messages.error'));
    }
    /**
     * Show Customer view page.
     *
     * @param Customer $users
     * @return json
     */
    public function profile(Customer $user)
    {

        // echo "hi";die;
        return view('admin.users.profile', [
            'user' => $user
        ]);
    }
    /**
     * Delete customer by unique idetifier.
     *
     * @return json
     */
    public function destroy(Customer $user)
    {
        EmailUsers::where("user_id", $user->id)->delete();
        DB::table('orders')
        ->where('customer_id', $user->id)
        ->update(['customer_id' => NULL]);

        DB::table('order_addresses')
        ->where('customer_id', $user->id)
        ->update(['customer_id' => NULL]);

        DB::table('product_review')
        ->where('user_id', $user->id)
        ->update(['user_id' => NULL]);


        DB::table('store_rating')
        ->where('user_id', $user->id)
        ->update(['user_id' => NULL]);

        DB::table('product_stock_history')
        ->where('user_id', $user->id)
        ->update(['user_id' => NULL]);
        
        DB::table('subscribers')
        ->where('user_id', $user->id)
        ->update(['user_id' => NULL]);

        Subscribers::where("user_id", $user->id)->delete();
        ProductCart::where("user_id", $user->id)->delete();
        ProductVisitor::where("user_id", $user->id)->delete();
        ProductLike::where("user_id", $user->id)->delete();
        StoreFollower::where("user_id", $user->id)->delete();
        StoreVisitor::where("user_id", $user->id)->delete();
        UserAddress::where("user_id", $user->id)->delete();
         

        if($user->delete()) {
            
            return redirect(route('users.index'))->with('success', trans('messages.users.deleted'));
        }

        return redirect(route('users.index'))->with('error', trans('messages.error'));
    }

    /**
     * Filter customer listing.
     *
     * @param $search
     * @return $query
     */
    private function filterCustomers($search, $query)
    {
        $query->where('first_name', 'like', '%'.$search.'%')
              ->orWhere('last_name', 'like', '%'.$search.'%')
              ->orWhere('mobile_no', 'like', '%'.$search.'%')
              ->orWhere('email', 'like', '%'.$search.'%');
    }
    
}
