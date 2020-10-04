<?php

/**
 * Created by PhpStorm.
 * User: Angat
 * Date: 2018-02-23
 * Time: 5:19 PM
 */

namespace App\Http\Controllers\Admin;

use App\EmailTemplate;
use App\EmailUsers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use App\Mail\BulkMail;

class AdminMailContoller extends Controller
{

    private $validationRules = [
        'name' => 'required|unique:email_templets,name',
        'email_content' => 'required',
        'subject' => 'required',
    ];

    /**
     * Display Mail template details.
     *
     * @return json
     */
    public function index()
    {
        return view('admin.emailTemplate.emailList');
    }

    /**
     * Search Email Templates.
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

            $query = EmailTemplate::select('name', 'email_content', 'subject', 'id');
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterEmail($request->search['value'], $query);

            $template = $query->orderBy($orderColumn, $orderDir)
                    ->paginate($request->length);

            $data = json_decode(json_encode($template));

            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $templates)
            {
                $templates->action = '<a href="' . url(route('emailTemplateEdit', ['email' => $templates->id])) . '" title="Edit"><i class="la la-edit"></i></a>' .
                        '<a class="delete-data" data-name="email template" href="' . url(route('deleteEmailTemplate', ['email' => $templates->id])) . '" title="Delete"><i class="la la-trash"></i></a>';
                $templates->action .= '<div class="dropdown">' .
                        '<button class="btn btn-primary" type="button" data-toggle="dropdown"><i class="fa fa-paper-plane" aria-hidden="true"></i>' .
                        '<span class="caret"></span></button>' .
                        '<ul class="dropdown-menu">' .
                        '<li><a href="' . url(route('emailTemplateSend', ['email' => $templates->id, 'type' => 'vendor'])) . '">Vendor</a></li>' .
                        '<li><a href="' . url(route('emailTemplateSend', ['email' => $templates->id, 'type' => 'customer'])) . '">Customer</a></li>' .
                        '<li><a href="' . url(route('emailTemplateSend', ['email' => $templates->id, 'type' => 'both'])) . '">Both</a></li>' .
                        '</ul>' .
                        '</div>' .
                        '</div>';
            }
            return response()->json($data);
        }
    }

    /* search mail templates */

    public function filterEmail($search, $query)
    {
        $query->where('subject', 'like', '%' . $search . '%')
                ->orWhere('email_content', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%');
    }

    /**
     * Display Mail template create page.
     *
     * @return json
     */
    public function create()
    {
        return view('admin.emailTemplate.emailCreate');
    }

    /**
     * Store Mail template.
     *
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);
        $emailTemplate = new EmailTemplate();
        $emailTemplate->fill($request->all());
        if ($emailTemplate->save())
        {
            return redirect(route('email-template.index'))->with('success', trans('messages.emailTemplate.added'));
        }
        return redirect(route('email-template.create'))->with('error', trans('messages.error'));
    }

    /* edit email templates */

    public function edit(EmailTemplate $email)
    {
        $data['emailTemplate'] = $email;
        return view('admin.emailTemplate.emailCreate', $data);
    }

    /* update email templates */

    public function update(Request $request, EmailTemplate $email)
    {
        $this->validationRules['name'] = 'unique:email_templets,name,' . $email->id;
        $this->validate($request, $this->validationRules);
        $email->fill($request->all());
        if ($email->save())
        {
            return redirect(route('email-template.index'))->with('success', trans('messages.emailTemplate.updated'));
        }
        return redirect(route('email-template.create'))->with('error', trans('messages.error'));
    }

    /**
     * Delete email by unique idetifier.
     *
     * @return json
     */
    public function destroy(EmailTemplate $email)
    {
        if ($email->delete())
        {

            return redirect(route('email-template.index'))->with('success', trans('messages.emailTemplate.deleted'));
        }

        return redirect(route('email-template.index'))->with('error', trans('messages.error'));
    }

    /* save mail data */

    public function saveEmail(Request $request, EmailTemplate $email, $type)
    {
        if ($type === 'both')
        {
            $userType = array ('vendor', 'customer');
            // $where = "where type IN ('vendor','customer)";
        }
        else
        {
            $userType = array ($type);
            // $where = "where type = '".$type."'";
        }

        $users = User::whereIn('type', $userType)->where('status', '1')
                        ->select(array ('id'))->get();
//dd($users);die;
        /*    $data =   "INSERT INTO bulk_email_users (templet_id, user_id)
          SELECT {$email->id}, id FROM users
          WHERE status = '1'
          ".$where; */

        $dataSet = [];
        foreach ($users as $list)
        {
            $dataSet[] = [
                'user_id' => $list->id,
                'templet_id' => $email->id,
            ];
        }

        $data = DB::table('bulk_email_users')->insert($dataSet);
        if ($data)
        {
            return redirect(route('email-template.index'))->with('success', trans('messages.emailTemplate.send'));
        }

        return redirect(route('email-template.index'))->with('error', trans('messages.error'));
    }

    //pending bulk mail
    public function sendEmailTemplate()
    {
        $SuccessMailIds = [];
        $emailTemplateData = EmailUsers::selectRaw("bulk_email_users.*,email_templets.name,email_templets.subject,email_templets.subject,email_templets.email_content,CONCAT(users.first_name,' ',users.last_name) as full_name,users.email")
                        ->join("email_templets", "email_templets.id", "bulk_email_users.templet_id")
                        ->join("users", "users.id", "bulk_email_users.user_id")
                        ->where('bulk_email_users.status', '0')->limit(5)->get();
        if ( ! empty($emailTemplateData))
        {
            $EmailUserIds = [];
            foreach ($emailTemplateData as $emailData)
            {
                Mail::to($emailData->email)->send(new BulkMail($emailData));
                $EmailUserIds[]=$emailData->id;
            }
            EmailUsers::whereIn('id', $EmailUserIds)->update(["status" => "1"]);
        }
    }

}
