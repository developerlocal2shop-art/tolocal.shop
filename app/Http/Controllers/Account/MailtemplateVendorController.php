<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\client\CompanyEmailTEmplate;

class MailtemplateVendorController extends Controller
{
    public function create(){
        if(Helper::getFeatureUses('mail') == false){
            return abort(404);
        }
        return view('account.company.mailtemplate.create');
    }
    public function submit(Request $request){
        $this->validate($request, [
           'mail_template_name'  => 'required',
           'template_design' => 'required'           
        ]);
        $mailtemplate_save = new CompanyEmailTEmplate;
        $mailtemplate_save->template_name          = $request->input('mail_template_name');
        $mailtemplate_save->template_design        = $request->input('template_design');
        $mailtemplate_save->save();
        return view('account.company.mailtemplate.create')->with('vendormailmsg', 'Email Template Added Successfully!');
    }
}
