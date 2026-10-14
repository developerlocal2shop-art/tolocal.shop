<?php

namespace App\Http\Controllers\Admin\Mailtemplate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\MailTemplate;

class MailtemplateController extends Controller
{
    public function create()
    {
        return view('admin.mailtemplate.create');
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'mail_template_name'  => 'required',
            'template_design' => 'required'
        ]);
        $mailtemplate_save = new MailTemplate;
        $mailtemplate_save->template_name          = $request->input('mail_template_name');
        $mailtemplate_save->template_design        = $request->input('template_design');
        $mailtemplate_save->save();
        return view('admin.mailtemplate.create')->with('msg', 'Email Template Added Successfully!');
    }
}
