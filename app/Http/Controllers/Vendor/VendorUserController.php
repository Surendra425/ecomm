<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
class VendorUserController extends Controller
{
    private $validationRules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|unique:users|email',
        'password' => 'required|confirmed',
    ];
    
    /**
     * Display Customer details.
     *
     * @return json
     */
    public function index()
    {
        return view('vendor.users.users_list');
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

                $query = Customer::select('id', 'first_name',  'last_name', 'email',  'credit', 'status', 'created_at', 'updated_at');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterCustomers($request->search['value'], $query);
            
            $customers = $query->orderBy($orderColumn, $orderDir)
                              ->paginate($request->length);
            
            $data = json_decode(json_encode($customers));
            
            $data->recordsFiltered = $data->recordsTotal = $data->total;
            
            foreach ($data->data as $customer) {
                
                $customer->action = '<a href="'.url(route('users.edit', ['user' => $customer->id ])).'" title="Edit"><i class="icon-edit"></i></a>'.
                                     '<a class="delete-data" data-name="user" href="'.url(route('deleteUser', ['user' => $customer->id ])).'" title="Delete"><i class="icon-android-delete"></i></a>';
                $customer->action .= ($customer->status) ? '<a href="'.url(route('changeUserStatus', ['user' => $customer->id ])).'" title="Disable"><i class="icon-eye-disabled"></i></a>'
                                                         : '<a href="'.url(route('changeUserStatus', ['user' => $customer->id ])).'" title="Enable"><i class="icon-eye"></i></a>';
                
                $customer->credit = '$'.$customer->credit;
                $customer->status = $customer->status ? 'Enable' : 'Disable';
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
        return view('vendor.users.user_create');
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
        $customer->credit = 0.00;
        $customer->password = bcrypt($request->password);

        if ($customer->save()) {

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
        return view('vendor.users.user_edit', [
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
        $this->validationRules['email'] = 'required|unique:users,email,'.$user->id.'|email';
        unset($this->validationRules['password']);
        $this->validate($request, $this->validationRules);
        
        $response = [
            'status' => 0,
            'message' => trans('messages.failed'),
        ];

            $user->fill($request->all());

            if ($user->save()) {

                return redirect(route('users.index'))->with('success', trans('messages.users.updated'));
            }

            return redirect(route('users.index'))->with('error', trans('messages.error'));
    }

    /**
     * Delete customer by unique idetifier.
     *
     * @return json
     */
    public function destroy(Customer $user)
    {
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
              ->orWhere('email', 'like', '%'.$search.'%');
    }
    
}
