<?php

namespace App\Http\Controllers\Customer;

use App\City;
use App\Mail\SubscriptionMail;
use App\Subscribers;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery\CountValidator\Exception;

class CommonController extends Controller
{
    /**
     * check unique column.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUnique(Request $request, $table, $columnName , $idColumnName=null, $id=null)
    {

        if($request->ajax()) {

            if(!empty($request->value)) {

                $where = [
                    [$columnName, '=', $request->value],
                ];

                if(!empty($request->id)) {
                    $where[] = [$idColumnName, '!=', $request->id];
                }

                $count = DB::table($table)
                    ->where($where)
                    ->count();

                return $count > 0 ?  'false' : 'true';
            }
        }
    }

    /**
     * get city using country id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getCityByCountry(Request $request){
        $cityList = [];
        if($request->country_id){
            $cityList = City::where('country_id',$request->country_id)->orderBy('city_name', 'ASC')->get();
        }
        return response()->json(['data'=>$cityList]);
    }

    /**
     * @param Request $request
     * @return \App\Http\Controllers\json
     */
    public function subscribe(Request $request){

        $email = $request->email;

        $user = User::where("email", "=", $email)->first();

        $subscriber = Subscribers::where("email", "=", $email)->first();

        if (!empty($subscriber))
        {
            return $this->toJson(null,trans('messages.subscription.already_subscribed'),0);
        }

        $subscriber = new Subscribers();

        $subscriber->email = $request->email;

        if (!empty($user))
        {
            $subscriber->user_id = $user->id;
        }

        if ($subscriber->save()){

            try
            {
                Mail::to($email)->send(new SubscriptionMail($subscriber));
                return $this->toJson(null,trans('messages.subscription.subscribe'),1);
            }
            catch (Exception $exc)
            {
                return $this->toJson(null,trans('messages.subscription.error'),0);
            }
        }
    }

    /**
     * Get customer vise product rating
     * @param Request $request
     * @param $table
     * @param $column
     * @return string
     */
    public function getProductReview(Request $request, $table, $column){

        if(!empty($request->value)) {
            $where = [
                [$column, '=', $request->value],
                ['product_combination_id', '=', $request->product_combination_id],
                ['user_id', '=', $request->user_id],
            ];

            $data = DB::table($table)
                ->where($where)
                ->get();

            return json_encode($data);
        }
    }

    /**
     * Show static app page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAppPage(){

         return redirect('http://onelink.to/ux2u7c');   
        //return view('front.app');
    }
}
