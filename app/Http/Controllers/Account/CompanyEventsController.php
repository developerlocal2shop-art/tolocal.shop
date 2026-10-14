<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\EventGallery;
use App\Models\Events;
use App\Helpers\Helper;
use App\Models\EventsTranslation;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
use App\Models\DirectorySeo;
use App\Models\DiectoryLocation;
use App\Models\Instruction;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CompanyEventsController extends Controller
{
    public function eventList()
    {
        if (Auth::check()) {
            if (Helper::getFeatureUses('events') == false) {
                return abort(404);
            }
            $instruction  = Instruction::select('message')->where('section_name', 'event')->where('status','Active')->first(); /* get instructionData */
            $eventsdata = Events::where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')
                ->orderBy('id', 'desc')->paginate(20);
            return view('account.company.events.index', compact('eventsdata', 'instruction'));
        } else {
            return abort(404);
        }
    }

    public function companyaddevents()
    {
        if (Auth::check()) {
            if (Helper::getFeatureUses('events') == false) {
                return abort(404);
            }
            $data['parent_category']    = Helper::getAllParentCategory();      /* get Category */
            $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
            return view('account.company.events.create', compact('data'));
        } else {
            return abort(404);
        }
    }

    public function companyaddeventssubmit(Request $request)
    {
        $this->validate($request, [
            'events_title_en'  => 'required',
            'category' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'event_start_date'      => 'required',
            'event_start_time'      => 'required',
            'event_end_date'        => 'required|after_or_equal:event_start_date',
            'event_end_time'        => 'required'
        ]);
        $name_en = $request->input('events_title_en');
        $description_en = $request->input('events_content_en');

        $latitude = $request->input('lati');
        $longitude = $request->input('long');
        $dirId = $request->input('directoryId'); 
        $dirlocationdetails = DiectoryLocation::where('directory_id', $dirId)->first();
        if (!empty($dirlocationdetails)) {
            if($dirlocationdetails['latitude']!=0 && $dirlocationdetails['longitude']!=0){
                $latitude = $dirlocationdetails['latitude'];
                $longitude = $dirlocationdetails['longitude']; 
            }
        }

        $events_inst = new Events;
        $imgfile = $request->file('image');
        $ext = $imgfile->getClientOriginalExtension();
        $name2 = $imgfile->getClientOriginalName();
        $imageName2 = time() . $name2;
        $imageName = Helper::getSeoUrl($imageName2);
        $imagepath = 'files/events/' . $imageName;
        $image_resize = Image::make($imgfile->getRealPath());
        $image_resize->save(public_path('files/events/' . $imageName));

        $events_inst->title             = (isset($name_en)) ? $name_en : "";
        $events_inst->slug              = Str::slug($name_en);
        $events_inst->category_id       = $request->input('category');
        $events_inst->content           = isset($description_en) ? $description_en : "";
        $events_inst->created_by        = $request->input('createdby');
        $events_inst->directory_id      = $request->input('directoryId');
        $events_inst->event_image       = $imageName;
        $events_inst->location          = $request->input('event_location');
        $events_inst->meta_description  = $request->input('meta_description')??'';
        $events_inst->start_date        = $request->input('event_start_date') ?? 0;
        $events_inst->start_time        = $request->input('event_start_time') ?? 0;
        $events_inst->end_date          = $request->input('event_end_date') ?? 0;
        $events_inst->end_time          = $request->input('event_end_time') ?? 0;
        $events_inst->status            = 'Active';
        $events_inst->display_status    = $request->input('display_status');
        $events_inst->latitude          = $latitude;
        $events_inst->longitude         = $longitude;
        $events_inst->externalLink      = $request->input('external_link');
        $events_inst->save();
        $event_id = $events_inst->id;

        $dirtseo = new DirectorySeo;
            $dirtseo->module_id     = $event_id;
            $dirtseo->page_type     = 'event';
            $dirtseo->meta_keyword  = $name_en??"";
            $dirtseo->meta_description  = $request->input('meta_description')??"";
            $dirtseo->save();

        $imgfilegal = $request->file('event_gal_image');
        if ($imgfilegal) {
            foreach ($imgfilegal as $imgrow) {
                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName = time() . $name2;
                $imagepath = 'files/events/' . $imageName;
                $image_resize = Image::make($imgrow->getRealPath());
                $image_resize->save(public_path('files/events/' . $imageName));
                $productmainimage_insert                    = new EventGallery;
                $productmainimage_insert->event_id          = $event_id;
                $productmainimage_insert->name_thumbnail    = $imageName;
                $productmainimage_insert->name_large        = $imageName;
                $productmainimage_insert->extension         = $ext;
                $productmainimage_insert->path              = $imagepath;
                $productmainimage_insert->save();
            }
        }

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
        foreach ($languages as $lang) {
            /*$title_name = $request->input('events_title_' . $lang->code);
            $description_code = $request->input('events_content_' . $lang->code); */

            $title_name                     = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'');
            $description_code               = $tr->setSource('en')->setTarget($lang->code)->translate($description_en??'');

            $eventstrans = new EventsTranslation;
            $eventstrans->event_id        = $event_id;
            $eventstrans->category_id     = 0;
            $eventstrans->language_id     = $lang->id;
            $eventstrans->title           = isset($title_name) ? $title_name : $name_en;
            $eventstrans->description     = isset($description_code) ? $description_code : $description_en;
            $eventstrans->seo_url         = Helper::getSeoUrl(isset($title_name) ? $title_name : $name_en);
            $eventstrans->save();
        }
        return redirect('company/events')->with('msg', 'Event Added Successfully!');
    }

    public function deleteGalleryImage(Request $request)
    {
        $data = EventGallery::find($request->id)->delete();
        return response()->json($data);
    }

    public function companyeventedit($id = 0)
    {
        if (Helper::getFeatureUses('events') == false) {
            return abort(404);
        }
        $user = auth()->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        if (Auth::check()) {
            $data['MetaTag'] = DB::table('directory_seo')->where('module_id', $id)->where('page_type','event')->first();

            $data['eventsDetails'] = Events::with('eventgallery')->findOrFail($id);
            $data['parent_category']    = Helper::getAllParentCategory();      /* get Category */
            $data['languagesData']      = Helper::getAllLanguages();             /* get languages */
            $data['eventsAndTraslationData'] = DB::table('events as e')->select('*')
                ->join('events_translation as et', 'e.id', '=', 'et.event_id')
                ->where('et.event_id',  $id)->get();
            return view('account.company.events.edit', ['data' => $data]);
        } else {
            return abort(404);
        }
    }

    public function companyeventupdate(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'event_gal_image' => 'array|max:20',
            'category' => 'required',
            'event_start_date'      => 'required',
            'event_start_time'      => 'required',
            'event_end_date'        => 'required|after_or_equal:event_start_date',
            'event_end_time'        => 'required'
        ],[
            'events_title_en.required'     => 'The Event Title field is required.',
        ]);

        $name_en = $request->input('events_title_en');

        $latitude = $request->input('lati');
        $longitude = $request->input('long');
        $dirId = $request->input('directoryId'); 
        $dirlocationdetails = DiectoryLocation::where('directory_id', $dirId)->first();
        if (!empty($dirlocationdetails)) {
            if($dirlocationdetails['latitude']!=0 && $dirlocationdetails['longitude']!=0){
                $latitude = $dirlocationdetails['latitude'];
                $longitude = $dirlocationdetails['longitude']; 
            }
        }

        
        $description_en = $request->input('events_content_en');
        $imgfile = $request->file('image');
        if ($imgfile != "") {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName = time() . $name2;
            $imagepath = 'files/events/' . $imageName;
            $image_resize = Image::make($imgfile->getRealPath());
            $image_resize->save(public_path('files/events/' . $imageName));
            Events::where('id', $id)->update(['event_image' => $imageName,]);
        }
        $imgfilegal = $request->file('event_gal_image');
        if ($imgfilegal) {
            foreach ($imgfilegal as $imgrow) {
                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName = time() . $name2;
                $imagepath = 'files/events/' . $imageName;
                $image_resize = Image::make($imgrow->getRealPath());
                $image_resize->save(public_path('files/events/' . $imageName));
                $productmainimage_insert                    = new EventGallery;
                $productmainimage_insert->event_id          = $id;
                $productmainimage_insert->name_thumbnail    = $imageName;
                $productmainimage_insert->name_large        = $imageName;
                $productmainimage_insert->extension         = $ext;
                $productmainimage_insert->path              = $imagepath;
                $productmainimage_insert->save();
            }
        } else {
            $thumb_hidden_img = $request->input('thumb_hidden_img');
            $hiddenarray_thumb_hidden_img = explode(',', $thumb_hidden_img);
            $details = EventGallery::where('event_id', $id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->name_thumbnail = $hiddenarray_thumb_hidden_img[$key];
                    $detail->name_large = $hiddenarray_thumb_hidden_img[$key];
                    $detail->path = 'files/events/' . $hiddenarray_thumb_hidden_img[$key];
                    $detail->save();
                }
            }
        }
        Events::where('id', $id)
            ->update([
                'title'             => $name_en,
                'slug'              => Str::slug($name_en),
                'category_id'       => $request->input('category'),
                'content'           => $description_en,
                'location'          => $request->input('event_location'),
                'meta_description'  => $request->input('meta_description'),
                'start_date'        => $request->input('event_start_date'),
                'start_time'        => $request->input('event_start_time'),
                'end_date'          => $request->input('event_end_date'),
                'end_time'          => $request->input('event_end_time'),
                'status'            => 'Active',
                'display_status'    => $request->input('display_status'),
                'latitude'          => $latitude,
                'longitude'         => $longitude,
                'externalLink'      => $request->input('external_link')
            ]);

        $module_id = $request->input('module_id');
        $page_type = $request->input('page_type');
        if (!empty($module_id) && (!empty($page_type))) {
            DirectorySeo::where('module_id', $id)->where('page_type', 'event')
            ->update([
                'page_type'  => 'event',
                'meta_keyword' => $name_en??"",
                'meta_description' => $request->input('meta_description')??""
            ]);  
        }else{
            $dirtseo = new DirectorySeo;
            $dirtseo->module_id     = $id;
            $dirtseo->page_type     = 'event';
            $dirtseo->meta_keyword  = $name_en??"";
            $dirtseo->meta_description  = $request->input('meta_description')??"";
            $dirtseo->save();
        }

        
        $is_not_change_en_language = EventsTranslation::where('language_id', 1)->where('title', $name_en)->where('description', $description_en)->exists();
            
        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();

        if($is_not_change_en_language){

             foreach ($languages as $lang) {
                $title_name = $request->input('events_title_' . $lang->code);
                $description_code = $request->input('events_content_' . $lang->code);
                $eventTranslation_id = $request->input('eventtranlation_' . $lang->code);

                $eventtrans =  EventsTranslation::find($eventTranslation_id);
                $eventtrans->title              = isset($title_name) ? $title_name : "";
                $eventtrans->description        = isset($description_code) ? $description_code : "";
                $eventtrans->seo_url            = Helper::getSeoUrl($title_name);
                $eventtrans->save();
            }
        }else{
            
            EventsTranslation::where('event_id', $id)->delete();
            foreach ($languages as $lang) {
                $title_name                     = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'');
                $description_code               = $tr->setSource('en')->setTarget($lang->code)->translate($description_en??'');

                $eventstrans = new EventsTranslation;
                $eventstrans->event_id        = $id;
                $eventstrans->category_id     = 0;
                $eventstrans->language_id     = $lang->id;
                $eventstrans->title           = isset($title_name) ? $title_name : $name_en;
                $eventstrans->description     = isset($description_code) ? $description_code : $description_en;
                $eventstrans->seo_url         = Helper::getSeoUrl(isset($title_name) ? $title_name : $name_en);
                $eventstrans->save();
            }

        }
        
        return redirect('company/events')->with('msg', 'Event has been updated');
    }
    public function destroy($id)
    {
        Events::where('id', $id)->update(['deleted' => '1']);
        return redirect('company/events')->with('msg', 'Event deleted successfully');
    }
}
