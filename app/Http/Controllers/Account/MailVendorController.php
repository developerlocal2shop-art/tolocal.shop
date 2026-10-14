<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\MailTemplate;
use Illuminate\Http\Request;
use App\Models\client\CompanyEmail;
use App\Models\client\CompanyEmailTEmplate;
use App\Mail\MailTemplates;
use Illuminate\Support\Facades\Mail;

class MailVendorController extends Controller
{

    public function index(Request $request){
        if(Helper::getFeatureUses('mail') == false){
            return abort(404);
        }
        $fromDate = $request->fromdate;
        $toDate = $request->todate;
        $searchQuery = $request->input('searchtext');
        $searchtext = isset($searchQuery)?$searchQuery:"";
        if($request->input('search')){
            $query = $request->all();
            $maildata = CompanyEmail::where('status', 'Active');
            if($searchtext!=""){
                $maildata = $maildata->where('to_email', 'like', '%' . $searchtext . '%')
                ->orWhere('subject', 'like', '%' . $searchtext . '%')
                ->orWhere('content', 'like', '%' . $searchtext . '%');
            }
            if($fromDate && $toDate){
                $maildata = $maildata->WhereBetween('send_date',[$fromDate, $toDate]);
            }
            if($fromDate){
                $maildata = $maildata->where('send_date',">=", $fromDate);
            }
            if($toDate){
                $maildata = $maildata->where('send_date',"<=", $toDate);
            }
            $maildata = $maildata->orderBy('id', 'desc')->paginate(20);
            return view('account.company.mail.listing',compact('maildata','searchtext','fromDate','toDate','query'));
        }else{
            $maildata = CompanyEmail::where('status', 'Active')->orderBy('id', 'desc')->paginate(20);
            
            return view('account.company.mail.listing',compact('maildata','searchtext','fromDate','toDate'));
        }
    }
    public function create(){
        if(Helper::getFeatureUses('mail') == false){
            return abort(404);
        }
        $mailtemplatedata = CompanyEmailTEmplate::get();
        return view('account.company.mail.create',compact('mailtemplatedata'));
    }
    public function getMailByTemplateId(Request $request){
        if(Helper::getFeatureUses('mail') == false){
            return abort(404);
        }
        $data['templatemaildata'] = CompanyEmailTEmplate::where("id", $request->template_id)->get(["template_design"]);
        return response()->json($data);
    }
    public function submit(Request $request){
        $this->validate($request, [
            'to_mail' => 'required',
	    	'subject_mail' => 'required',
	    ]);
        $listId = env('MAILCHIMP_LIST_ID');
        $mailchimp = new \Mailchimp(env('MAILCHIMP_API_KEY'));
        Mail::to($request->input('to_mail'))->send(new MailTemplates($request->all()));
    
            $mail_save = new CompanyEmail;
            $mail_save->to_email        = $request->input('to_mail');
            $mail_save->subject         = $request->input('subject_mail');
            $mail_save->template_id     = $request->input('template_mail');
            $mail_save->content         = $request->input('content_mail');
            $mail_save->send_date       = date('Y-m-d');
            $mail_save->save();
        
        return redirect('/company/mail')->with('vendormailmsg', 'Email send Successfully!');
    }
    public function view($id=0){
        $mailData = CompanyEmail::findOrFail($id);
        return view('account.company.mail.view',compact('mailData'));
    }
}
