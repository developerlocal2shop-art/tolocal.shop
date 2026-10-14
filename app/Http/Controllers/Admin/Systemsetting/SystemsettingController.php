<?php

namespace App\Http\Controllers\Admin\Systemsetting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Systemsetting;
use App\Models\Recaptchasetting;

class SystemsettingController extends Controller
{
    public function index()
    {
        $data = array();
        $settingDetails = Systemsetting::get()->toarray();
        if (isset($settingDetails[0])) {
            $settingDetails = $settingDetails[0];
        } else {
            $settingDetails['country'] = "";
            $settingDetails['timezone'] = "";
            $settingDetails['system_language'] = "";
            $settingDetails['id'] = "";
        }
        $captchasettingDetails = Recaptchasetting::get()->toarray();
        if (isset($captchasettingDetails[0])) {
            $captchasettingDetails = $captchasettingDetails[0];
        } else {
            $captchasettingDetails['id'] = "";
        }
        $data['settingDetails'] = $settingDetails;
        $data['captchasettingDetails'] = $captchasettingDetails;
        return view('admin.systemsetting.index', ['data' => $data]);
    }

    public function submitSystemsetting(Request $request)
    {
        $this->validate($request, [
            'website_title'  => 'required',
            'system_title' => 'required',
            'system_email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'timezone' => 'required',
            'system_language' => 'required',
            'site_key' => 'required',
            'secret_key' => 'required'
        ]);
        $setting = new Systemsetting;
        $setting->website_title     = $request->input('website_title');
        $setting->system_title      = $request->input('system_title');
        $setting->meta_keyword      = $request->input('meta_keyword');
        $setting->meta_description  = $request->input('meta_description');
        $setting->system_email      = $request->input('system_email');
        $setting->address           = $request->input('address');
        $setting->phone             = $request->input('phone');
        $setting->country           = $request->input('country');
        $setting->timezone          = $request->input('timezone');
        $setting->system_language   = $request->input('system_language');
        $setting->footer_text       = $request->input('footer_text');
        $setting->footer_link       = $request->input('footer_link');
        $setting->save();
        $captchasetting = new Recaptchasetting;
        $captchasetting->site_key     = $request->input('site_key');
        $captchasetting->secret_key   = $request->input('secret_key');
        $captchasetting->save();
        return redirect('admin/systemsetting-edit')->with('msg', 'System setting updated Successfully!');
    }
    public function editSystemsetting()
    {
        $data = array();
        $settingDetails = Systemsetting::get()->toarray();
        if (isset($settingDetails[0])) {
            $settingDetails = $settingDetails[0];
        } else {
            $settingDetails['country'] = "";
            $settingDetails['timezone'] = "";
            $settingDetails['system_language'] = "";
            $settingDetails['id'] = "";
        }
        $captchasettingDetails = Recaptchasetting::get()->toarray();
        if (isset($captchasettingDetails[0])) {
            $captchasettingDetails = $captchasettingDetails[0];
        } else {
            $captchasettingDetails['id'] = "";
        }
        $data['settingDetails'] = $settingDetails;
        $data['captchasettingDetails'] = $captchasettingDetails;
        return view('admin.systemsetting.index', ['data' => $data]);
    }

    public function updateSystemsetting(Request $request)
    {
        $this->validate($request, [
            'website_title'  => 'required',
            'system_title' => 'required',
            'system_email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'timezone' => 'required',
            'system_language' => 'required',
            'site_key' => 'required',
            'secret_key' => 'required'
        ]);
        Systemsetting::where('id', $request->input('id'))
            ->update([
                'website_title' => $request->input('website_title'),
                'system_title' => $request->input('system_title'),
                'meta_keyword' => $request->input('meta_keyword'),
                'meta_description' => $request->input('meta_description'),
                'system_email' => $request->input('system_email'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'country' => $request->input('country'),
                'timezone' => $request->input('timezone'),
                'system_language' => $request->input('system_language'),
                'footer_text' => $request->input('footer_text'),
                'footer_link' => $request->input('footer_link')
            ]);
        Recaptchasetting::where('id', $request->input('captcha_id'))
            ->update([
                'site_key' => $request->input('site_key'),
                'secret_key' => $request->input('secret_key')
            ]);
        return redirect('admin/systemsetting-edit')->with('msg', 'System setting updated Successfully!');
    }
}
