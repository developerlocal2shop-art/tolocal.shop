<?php

namespace App\Http\Controllers\Admin\Smtpsetting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Smtpsetting;

class SmtpsettingController extends Controller
{
    public function index()
    {
        $settingDetails = Smtpsetting::get()->toarray();
        if (isset($settingDetails[0])) {
            $settingDetails = $settingDetails[0];
        } else {
            $settingDetails['id'] = "";
        }
        return view('admin.smtpsetting.index', ['settingDetails' => $settingDetails]);
    }

    public function submitSmtpsetting(Request $request)
    {
        $this->validate($request, [
            'smtp_protocol'  => 'required',
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_user' => 'required',
            'smtp_password' => 'required'
        ]);
        $user_id = 0;
        $setting = new Smtpsetting;
        $setting->smtp_protocol = $request->input('smtp_protocol');
        $setting->smtp_host      = $request->input('smtp_host');
        $setting->smtp_port  = $request->input('smtp_port');
        $setting->smtp_user  = $request->input('smtp_user');
        $setting->smtp_password  = $request->input('smtp_password');
        $setting->created_by       = $user_id;
        $setting->save();
        return redirect('admin/smtpsetting-edit')->with('msg', 'SMTP setting updated Successfully!');
    }
    public function editSmtpsetting()
    {
        $settingDetails = Smtpsetting::get()->toarray();
        return view('admin.smtpsetting.index', ['settingDetails' => $settingDetails[0]]);
    }

    public function updatesmtpsetting(Request $request)
    {
        $this->validate($request, [
            'smtp_protocol'  => 'required',
            'smtp_host' => 'required',
            'smtp_port' => 'required',
            'smtp_user' => 'required',
            'smtp_password' => 'required'
        ]);
        Smtpsetting::where('id', $request->input('id'))
            ->update([
                'smtp_protocol' => $request->input('smtp_protocol'),
                'smtp_host' => $request->input('smtp_host'),
                'smtp_port' => $request->input('smtp_port'),
                'smtp_user' => $request->input('smtp_user'),
                'smtp_password' => $request->input('smtp_password')
            ]);
        return redirect('admin/smtpsetting-edit')->with('msg', 'SMTP setting updated Successfully!');
    }
}
