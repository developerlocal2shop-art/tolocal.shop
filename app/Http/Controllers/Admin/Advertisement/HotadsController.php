<?php

namespace App\Http\Controllers\Admin\Advertisement;

use App\Exports\CompetitionAnswerExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Advertisement;
use App\Helpers\Helper;
use App\Mail\PrizeCodeMail;
use App\Mail\PrizeCodeVendorMail;
use App\Models\AdvertisementAnswers;
use App\Models\AdvertisementQuestions;
use App\Models\CompetitionWinners;
use App\Models\HotAds;
use App\Models\HotAdTranslation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManagerStatic as Image;
use Stichoza\GoogleTranslate\GoogleTranslate;

class HotadsController extends Controller
{
    public function index(Request $request)
    {

        $searchtext = $request->searchtext ?? '';
        $user_type   = $request->user_type ?? '';

        $hotads = HotAds::query()
            ->where('deleted', '0')
            ->where('heading', 'like', "%{$searchtext}%")
            ->when($user_type !== '', function ($query) use ($user_type) {
                if ($user_type === '0') {
                } elseif ($user_type === 'admin') {
                    $query->whereNull('company_id');
                } else {
                    $query->whereNotNull('company_id');
                }
            })
            ->orderByDesc('id')
            ->where(function ($q) {
                $q->where('company_id', 0)
                ->orWhereNull('company_id')
                ->orWhere('company_id', '');
            })
            ->paginate($searchtext ? 50 : 20);

        return view('admin.hotads.index', compact('hotads','searchtext','user_type'));
    }

    public function create()
    {
        $data['languagesData']      = Helper::getAllLanguages();
        return view('admin.hotads.create', compact('data'));
    }

    public function changeHotAdStatus(Request $request)
    {
        $product = HotAds::find($request->id);
        $product->status = $request->status;
        $product->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'heading_en' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $imgfile = $request->file('image');
        $startdate  = $request->input('start_date');
        $expiredate = $request->input('end_date');

        $name_en = $request->input('heading_en') ?? '';
        $seo_en = $request->input('seo_en');
        $description_en = $request->input('description_en');

        /* |-------------- | Short Video Upload |-------------------- */
        $short_video_name = '';
        if ($request->hasFile('short_video')) {
            $video = $request->file('short_video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            //$videoName = Helper::getSeoUrl($videoName);
            $videoPath = public_path('files/advertisement/videos');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0755, true);
            }
            $video->move($videoPath, $videoName);
            $short_video_name = $videoName;
        }

        /* |---------------- | MP3 Upload |------------------------ */
        $mp3_file_name = '';
        if ($request->hasFile('mp3_file')) {
            $audio = $request->file('mp3_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            //$audioName = Helper::getSeoUrl($audioName);
            $audioPath = public_path('files/advertisement/audio');
            if (!file_exists($audioPath)) {
                mkdir($audioPath, 0755, true);
            }
            $audio->move($audioPath, $audioName);
            $mp3_file_name = $audioName;
        }

        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/advertisement/' . $imageName;
            $image_resize = Image::make($imgfile->getRealPath());
            $image_resize->save(public_path('files/advertisement/' . $imageName));

            $hotAd_insert                       = new HotAds;
            $hotAd_insert->heading              = $name_en;
            $hotAd_insert->image_name           = $imageName;
            $hotAd_insert->short_video          = $short_video_name??'';
            $hotAd_insert->mp3_file             = $mp3_file_name??'';
            $hotAd_insert->link                 = $request->input('link');
            $hotAd_insert->description          = $description_en;
            $hotAd_insert->start_date           = isset($startdate) ? $startdate : date("Y-m-d");
            $hotAd_insert->end_date             = isset($expiredate) ? $expiredate : date("Y-m-d");
            $hotAd_insert->company_id           = $request->input('directory_id')??0;
            $hotAd_insert->display_home         = $request->input('is_home');
            $hotAd_insert->video_display_on_home  = $request->input('video_display_on_home');
            $hotAd_insert->save();
            $hotAd_id = $hotAd_insert->id;


            $languages = Helper::getAllLanguages();
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            foreach ($languages as $lang) {
                $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
                $description_code           = $tr->setSource('en')->setTarget($lang->code)->translate($description_en);
                $title_name                 = isset($title_name) ? $title_name : $name_en;
                $trans                  = new HotAdTranslation();
                $trans->hotad_id       = $hotAd_id;
                $trans->language_id     = $lang->id;
                $trans->title           = isset($title_name) ? $title_name : $name_en;
                $trans->description     = isset($description_code) ? $description_code : $description_en;
                $trans->seo_url         = Helper::getSeoUrl(isset($title_name) ? $title_name : $name_en);
                $trans->save();
            }


        }
        return redirect('admin/hotads')->with('msg', 'Hot Ad Added Successfully!');
    }

    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $data['languagesData']      = Helper::getAllLanguages();
        $data['hotAdsTraslationData'] = DB::table('hot_ads as b')
            ->select('bt.id', 'bt.language_id', 'bt.title', 'bt.description')
            ->join('hot_ad_translations as bt', 'b.id', '=', 'bt.hotad_id')
            ->where('bt.hotad_id',  $id)->get();
        $hotAdDetails = HotAds::findOrFail($id);
        return view('admin.hotads.edit')->with(compact(['hotAdDetails', 'data']));
    }

    public function update(Request $request, $id)
    {

        //dd($request->all());
        $this->validate($request, [
            'heading_en'  => 'required',
            'start_date'  => 'required',
            'end_date'    => 'required',
            //'short_video' => 'nullable|mimes:mp4,mov,avi,webm',
            //'mp3_file'    => 'nullable|mimes:mp3,wav,ogg',
        ]);
        $startdate  = $request->input('start_date');
        $expiredate = $request->input('end_date');

        $name_en = $request->input('heading_en') ?? '';
        $seo_en = $request->input('seo_en');
        $description_en = $request->input('description_en');

        $updateArray = [
            'heading'              => $name_en,
            'link'                 => $request->input('link'),
            'description'          => $description_en,
            'display_home'         => $request->input('is_home')??0,
            'video_display_on_home' => $request->input('video_display_on_home'),
            'start_date'           => isset($startdate) ? $startdate : date("Y-m-d"),
            'end_date'             => isset($expiredate) ? $expiredate : date("Y-m-d"),
        ];
        $imgfile = $request->file('image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/advertisement/' . $imageName;
            $image_resize = Image::make($imgfile->getRealPath());
            $image_resize->save(public_path('files/advertisement/' . $imageName));
            $updateArray['image_name']      = $imageName;
        }

        /* |-------------- | Short Video Upload |-------------------- */
        if ($request->hasFile('short_video')) {
            $video = $request->file('short_video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            //$videoName = Helper::getSeoUrl($videoName);
            $videoPath = public_path('files/advertisement/videos');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0755, true);
            }
            $video->move($videoPath, $videoName);
            $updateArray['short_video'] = $videoName;
        }

        /* |---------------- | MP3 Upload |------------------------ */
        if ($request->hasFile('mp3_file')) {
            $audio = $request->file('mp3_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            //$audioName = Helper::getSeoUrl($audioName);
            $audioPath = public_path('files/advertisement/audio');
            if (!file_exists($audioPath)) {
                mkdir($audioPath, 0755, true);
            }
            $audio->move($audioPath, $audioName);
            $updateArray['mp3_file'] = $audioName;
        }


        HotAds::where('id', $id)->update($updateArray);
        $is_not_change_en_language = HotAdTranslation::where('title',$name_en)->where('description', $description_en)->where('language_id',1)->where('hotad_id',$id)->exists();
        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();
        if($is_not_change_en_language){
            foreach ($languages as $lang) {

                $title_name = $request->input('heading_' . $lang->code);
                $description_code = $request->input('description_' . $lang->code);
                $hotadTranslation_id = $request->input('hotadtranslation_' . $lang->code);

                $htrans                  =  HotAdTranslation::find($hotadTranslation_id);
                $htrans->title           = $title_name??$name_en;
                $htrans->description     = $description_code??$description_en;
                $htrans->seo_url         = Helper::getSeoUrl($title_name);
                $htrans->save();
            }
        }else{
            HotAdTranslation::where('hotad_id', $id)->delete();
            foreach ($languages as $lang) {
                $title_name                     = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'');
                $description_code               = $tr->setSource('en')->setTarget($lang->code)->translate($description_en??'');

                $htrans                      = new HotAdTranslation;
                $htrans->hotad_id             = $id;
                $htrans->language_id         = $lang->id;
                $htrans->title               = isset($title_name) ? $title_name : $name_en;
                $htrans->description         = isset($description_code) ? $description_code : $description_en;
                $htrans->seo_url             = Helper::getSeoUrl(isset($title_name) ? $title_name : $name_en);
                $htrans->save();
            }
        }
        return redirect('admin/hotads-edit/' . $id)->with('msg', 'Hot Ad has been updated');
    }

    public function deleteMp3($id)
    {
        $hotAd = HotAds::findOrFail($id);

        if (!empty($hotAd->mp3_file)) {
            $filePath = public_path('files/advertisement/audio/' . $hotAd->mp3_file);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $hotAd->mp3_file = '';
            $hotAd->save();
        }

        return redirect()->back()->with('msg', 'MP3 file deleted successfully.');
    }

    public function destroy($id)
    {
        HotAdTranslation::where('hotad_id', $id)->delete();
        HotAds::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/hotads')->with('msg', 'Deleted successfully');
    }
}
