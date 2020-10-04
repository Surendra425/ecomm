<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\UserContactUs;

class ContactUsController extends Controller
{

    private $validationRules = [
        'email' => 'required',
        'subject' => 'required',
        'description' => 'required'
    ];

    public function index()
    {
        $data = [];
        $customer = Auth::guard('customer')->user();
        $data['customer'] = $customer;
        return view('app.contact_us', $data);
    }

    public function call_us()
    {
        return view('app.call_us');
    }

    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        // Validate fields
        $this->validate($request, $this->validationRules);
        $conatctUs = new UserContactUs();
        $conatctUs->fill($request->all());
        if ( ! empty($customer))
        {
            $conatctUs->user_id = $customer->id;
        }
        if ($conatctUs->save())
        {
            return redirect(route('contact-us.index'))->with('success', trans('messages.contact_us.success'));
        }
        return redirect(route('contact-us.index'))->with('error', trans('messages.error'));
    }

}
