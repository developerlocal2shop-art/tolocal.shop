<?php

namespace App\Http\Controllers\Admin\Frontendsetting;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Frontendsetting;
use Intervention\Image\ImageManagerStatic as Image;

class FrontendsettingController extends Controller
{
    public function index()
    {
        $settingDetails = Frontendsetting::get()->toarray();
        if (isset($settingDetails[0])) {
            $settingDetails = $settingDetails[0];
        } else {
            $settingDetails['id'] = "";
            $settingDetails['active'] = "0";
        }
        
        return view('admin.frontendsetting.index', ['settingDetails' => $settingDetails]);
    }

    public function submitFrontendsetting(Request $request)
    {
        //dd($request->input('hide_show_vendor_contact'));
        $this->validate($request, [
            'banner_title'      => 'required',
            'cookie_note'       => 'required',
            'active'            => 'required',
            'cookie_policy'     => 'required',
            'logo_image'        => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        $user_id = 0;
        $setting = new Frontendsetting;
        $setting->banner_title                  = $request->input('banner_title');
        $setting->banner_sub_title              = $request->input('banner_sub_title');
        $setting->bannertype                    = $request->input('bannertype');
        $setting->slogan                        = $request->input('slogan');
        $setting->cookie_note                   = $request->input('cookie_note');
        $setting->active                        = $request->input('active');
        $setting->cookie_policy                 = $request->input('cookie_policy');
        $setting->about_us                      = $request->input('about_us');
        $setting->terms_and_condition           = $request->input('terms_and_condition');
        $setting->privacy_policy                = $request->input('privacy_policy');
        $setting->faq                           = $request->input('faq');
        $setting->facebook_link                 = $request->input('facebook_link');
        $setting->twitter_link                  = $request->input('twitter_link');
        $setting->linkedin_link                 = $request->input('linkedin_link');
        $setting->google_link                   = $request->input('google_link');
        $setting->instagram_link                = $request->input('instagram_link');
        $setting->pinterest_link                = $request->input('pinterest_link');
        $setting->taxval                        = $request->input('taxval');
        $setting->flat_rate                     = $request->input('flat_rate');
        $setting->shipping_value                = $request->input('shipping_value');
        $setting->hide_show_vendor_contact      = $request->input('hide_show_vendor_contact');
        $setting->commission_default_value      = $request->input('commission_val');
        $setting->default_competition_expiry       = $request->input('default_competition_expiry');
        $imgfile                                = $request->file('logo_image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName = time() . $name2;
            $imagepath = 'files/logo/' . $imageName;
            $imgfile->move(public_path('files/logo/'), $imageName);

            $setting->logo_name_thumbnail   = $imageName;
            $setting->logo_name_large       = $imageName;
            $setting->logo_extension        = $ext;
            $setting->logo_path             = $imagepath;
            $setting->save();
        }
        $bannerType = $request->input('bannertype');
        if ($bannerType == 'banner') {
            $bannerimgfile = $request->file('banner_image');
            if ($bannerimgfile) {
                $banner_ext = $bannerimgfile->getClientOriginalExtension();
                $name2 = $bannerimgfile->getClientOriginalName();
                $imageName2 = time() . $name2;
                $banner_imageName = Helper::getSeoUrl($imageName2);
                $banner_imagepath = 'files/banner/' . $imageName;
                $image_resize = Image::make($bannerimgfile->getRealPath());
                $image_resize->save(public_path('files/banner/' . $imageName));
                $setting->logo_name_thumbnail   = $banner_imageName;
                $setting->logo_name_large       = $banner_imageName;
                $setting->logo_extension        = $banner_ext;
                $setting->logo_path             = $banner_imagepath;
            }
        }
        if ($bannerType == 'video') {
            $bannervideofile = $request->file('banner_video');
            if ($bannervideofile) {
                $banner_ext = $bannervideofile->getClientOriginalExtension();
                $name2 = $bannervideofile->getClientOriginalName();
                $banner_videoName = time() . $name2;
                $banner_videopath = 'files/banner/' . $banner_videoName;
                $bannervideofile->move(public_path('files/banner/'), $banner_videoName);
                $setting->logo_name_thumbnail   = $banner_videoName;
                $setting->logo_name_large       = $banner_videoName;
                $setting->logo_extension        = $banner_ext;
                $setting->logo_path             = $banner_videopath;
            }
        }
        $setting->save();
        return redirect('admin/frontendsetting-edit')->with('msg', 'System setting updated Successfully!');
    }
    public function editFrontendsetting()
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $settingDetails = Frontendsetting::get()->toarray();
        return view('admin.frontendsetting.index', ['settingDetails' => $settingDetails[0]]);
    }

    public function updatefrontendsetting(Request $request)
    {

       // dd($request->all());

        $this->validate($request, [
            'banner_title'                  => 'required',
            'cookie_note'                   => 'required',
            'active'                        => 'required',
            'cookie_policy'                 => 'required',
            'logo_image'                    => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $update = ['banner_title' => $request->input('banner_title'), 'banner_sub_title' => $request->input('banner_sub_title'), 'bannertype' => $request->input('bannertype')];
        $imgfile = $request->file('logo_image');
        if ($imgfile) {
            $logo_ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $logo_imageName = time() . $name2;
            $logo_imagepath = 'files/logo/' . $logo_imageName;
            $imgfile->move(public_path('files/logo/'), $logo_imageName);
            $update['logo_name_thumbnail'] = "$logo_imageName";
            $update['logo_name_large'] = "$logo_imageName";
            $update['logo_extension'] = "$logo_ext";
            $update['logo_path'] = "$logo_imagepath";
        }
        if ($request->input('bannertype') == 'banner') {
            $bannerimgfile = $request->file('banner_image');
            if ($bannerimgfile) {
                $banner_ext = $bannerimgfile->getClientOriginalExtension();
                $name2 = $bannerimgfile->getClientOriginalName();
                $imageName2 = time() . $name2;
                $banner_imageName = Helper::getSeoUrl($imageName2);
                $banner_imagepath = 'files/banner/' . $bannerimgfile;
                //$image_resize = Image::make($bannerimgfile->getRealPath());
                //$image_resize->save(public_path('files/banner/' . $bannerimgfile));
                $bannerimgfile->move(public_path('files/banner/'), $banner_imageName);
                $update['banner_name_thumbnail'] = "$banner_imageName";
                $update['banner_name_large'] = "$banner_imageName";
                $update['banner_extension'] = "$banner_ext";
                $update['banner_path'] = "$banner_imagepath";
            }
        }
        if ($request->input('bannertype') == 'video') {
            $bannervideofile = $request->file('banner_video');
            if ($bannervideofile) {
                $banner_ext = $bannervideofile->getClientOriginalExtension();
                $name2 = $bannervideofile->getClientOriginalName();
                $imageName2 = time() . $name2;
                $banner_imageName = Helper::getSeoUrl($imageName2);
                $banner_imagepath = 'files/banner/' . $banner_imageName;
                $bannervideofile->move(public_path('files/banner/'), $banner_imageName);
                $update['banner_name_thumbnail'] = "$banner_imageName";
                $update['banner_name_large'] = "$banner_imageName";
                $update['banner_extension'] = "$banner_ext";
                $update['banner_path'] = "$banner_imagepath";
            }
        }
        $update['slogan']                       = $request->input('slogan');
        $update['cookie_note']                  = $request->input('cookie_note');
        $update['active']                       = $request->input('active');
        $update['cookie_policy']                = $request->input('cookie_policy');
        $update['about_us']                     = $request->input('about_us');
        $update['terms_and_condition']          = $request->input('terms_and_condition');
        $update['privacy_policy']               = $request->input('privacy_policy');
        $update['faq']                          = $request->input('faq');
        $update['facebook_link']                = $request->input('facebook_link');
        $update['twitter_link']                 = $request->input('twitter_link');
        $update['linkedin_link']                = $request->input('linkedin_link');
        $update['google_link']                  = $request->input('google_link');
        $update['instagram_link']               = $request->input('instagram_link');
        $update['pinterest_link']               = $request->input('pinterest_link');
        $update['taxval']                       = $request->input('taxval');
        $update['flat_rate']                    = $request->input('flat_rate');
        $update['shipping_value']               = $request->input('shipping_value');
        $update['hide_show_vendor_contact']    = $request->input('hide_show_vendor_contact');
        $update['commission_default_value']     = $request->input('commission_val');
        $update['default_competition_expiry']  = $request->input('default_competition_expiry');
        Frontendsetting::where('id', $request->input('id'))->update($update);
        return redirect('admin/frontendsetting-edit')->with('msg', 'System setting updated Successfully!');
    }
}
