<?php

namespace App\Http\Controllers\Customer;

use App\UserContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    /**
     * validation rules for contractus form
     * @var array
     */
    private $validationRules = [
        'email' => 'required|email',
        'subject' => 'required',
        'description' => 'required'
    ];

    /**
     * view of call us page
     * @return View
     */
    public function call_us()
    {
        return view('front.call_us');
    }

    /**
     * contact us form
     * @return View
     */
    public function index()
    {
        $data = [];
        $customer = \Auth::guard('customer')->user();
        $data['customer'] = $customer;
        return view('front.contact_us', $data);
    }

    /**
     * create new vendor
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request){

        $this->validate($request,$this->validationRules);

        $customer = \Auth::guard('customer')->user();

        $conatctUs = new UserContactUs();

        $conatctUs->fill($request->all());

        if ( ! empty($customer))
        {
            $conatctUs->user_id = $customer->id;
        }

        if ($conatctUs->save())
        {
            return redirect(route('contactUsIndex'))->with('success', trans('messages.contact_us.success'));
        }

        return redirect(route('contactUsIndex'))->with('error', trans('messages.contact_us.error'));
    }

}
