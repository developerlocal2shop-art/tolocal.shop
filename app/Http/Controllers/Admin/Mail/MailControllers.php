<?php

namespace App\Http\Controllers\Admin\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MailTemplate;
use App\Models\AdminEmail;
use Carbon\Carbon;
use App\Mail\MailTemplates;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Models\DiectoryLocation;


class MailControllers extends Controller
{
    public function listing(Request $request)
    {
        $fromDate = $request->fromdate;
        $toDate = $request->todate;
        $searchQuery = $request->input('searchtext');
        $searchtext = isset($searchQuery) ? $searchQuery : "";
        if ($request->input('search')) {
            $query = $request->all();
            $maildata = AdminEmail::where('status', 'Active');
            if ($searchtext != "") {
                $maildata = $maildata->where('to_email', 'like', '%' . $searchtext . '%')
                    ->orWhere('subject', 'like', '%' . $searchtext . '%')
                    ->orWhere('content', 'like', '%' . $searchtext . '%');
            }
            if ($fromDate && $toDate) {
                $maildata = $maildata->WhereBetween('send_date', [$fromDate, $toDate]);
            }
            if ($fromDate) {
                $maildata = $maildata->where('send_date', ">=", $fromDate);
            }
            if ($toDate) {
                $maildata = $maildata->where('send_date', "<=", $toDate);
            }
            $maildata = $maildata->orderBy('id', 'desc')->paginate(20);
            return view('admin.mail.listing', compact('maildata', 'searchtext', 'fromDate', 'toDate', 'query'));
        } else {
            $maildata = AdminEmail::where('status', 'Active')->orderBy('id', 'desc')->paginate(20);
            return view('admin.mail.listing', compact('maildata', 'searchtext', 'fromDate', 'toDate'));
        }
    }
    public function create()
    {
        $city = DiectoryLocation::select('city_id')->where('city_id', '<>', " ")->groupBy('city_id')->get();
        $postcode = DiectoryLocation::select('zipcode')->where('zipcode', '<>', " ")->groupBy('zipcode')->get();
        $mailtemplatedata = MailTemplate::get();
        return view('admin.mail.create', compact('mailtemplatedata', 'city', 'postcode'));
    }
    public function getMailByTemplateId(Request $request)
    {
        $data['templatemaildata'] = MailTemplate::where("id", $request->template_id)->get(["template_design"]);
        return response()->json($data);
    }

    public function sendMail(Request $request)
    {
        $this->validate($request, [
            'subject_mail' => 'required',
        ]);
        if ($request->selectedcity != "none") {
            $totalCustomer = DiectoryLocation::with('directoryowner')->where('city_id', $request->selectedcity)->get();
        }
        if ($request->selectedpostcode != "none") {
            $totalCustomer = DiectoryLocation::with('directoryowner')->where('zipcode', $request->selectedpostcode)->get();
        }
        $allmailids = array();
        foreach ($totalCustomer as $emailto) {
            $allmailids[] = $emailto->directoryowner->email;
        }
        Mail::to($allmailids)->send(new MailTemplates($request->all()));
        //Send campaign
        //$mailcampaignId = $mailchimp->campaigns->send($campaign['id']);
        $mail_save = new AdminEmail;
        $mail_save->to_email        = implode(",", $allmailids);
        $mail_save->subject         = $request->input('subject_mail');
        $mail_save->template_id     = $request->input('template_mail');
        $mail_save->content         = $request->input('content_mail');
        $mail_save->send_date       = date('Y-m-d');
        $mail_save->save();
        return redirect('/admin/mail')->with('adminEmailmsg', 'Email send Successfully!');
    }
    public function view($id = 0)
    {
        $mailData = AdminEmail::findOrFail($id);
        return view('admin.mail.view', compact('mailData'));
    }
}
