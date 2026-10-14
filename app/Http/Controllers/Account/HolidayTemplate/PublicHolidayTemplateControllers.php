<?php

namespace App\Http\Controllers\Account\HolidayTemplate;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\HolidayTemplateName;
use App\Models\HolidayTemplateDetails;

class PublicHolidayTemplateControllers extends Controller
{
    public function companyaddcustomTemplate()
    {
        if (Auth::check()) {
            if (Helper::getFeatureUses('schedule-all') == false) {
                return abort(404);
            }
            if (Auth::user()->profile_status == 0) {
                return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
            } else {
                $templatedata = HolidayTemplateName::query()->where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                return view('account.company.publicholidaytemplate.index', ['templatedata' => $templatedata]);
            }
        } else {
            return abort(404);
        }
    }
    public function createtemplate()
    {
        return view('account.company.publicholidaytemplate.create');
    }
    public function submittemplate(Request $request)
    {
        $this->validate($request, [
            'template_name'  => 'required',
        ]);
        $tempname = new HolidayTemplateName;
        $tempname->name             = $request->input('template_name');
        $tempname->directory_id     = $request->input('directoryId');
        $tempname->created_by       = $request->input('createdby');
        $tempname->save();
        $templateid = $tempname->id;
        $custom_date = $request->input('custom_date');
        $note = $request->input('note');
        $time_hours_min = $request->input('time_hours_min');
        $time_hours_max = $request->input('time_hours_max');
        $final_custom_time = [];
        if (count($time_hours_min)) {
            for ($i = 0; $i < count($time_hours_min); $i++) {
                $arr['open_time'] = $time_hours_min[$i];
                $arr['close_time'] = $time_hours_max[$i];
                $final_custom_time[] = $arr;
            }
        }
        $final_custom_time = json_encode($final_custom_time);
        $dirctcustom_holiday = new HolidayTemplateDetails;
        $dirctcustom_holiday->template_id = $templateid;
        $dirctcustom_holiday->custom_date = $custom_date;
        $dirctcustom_holiday->note = $note;
        $dirctcustom_holiday->custom_time = $final_custom_time;
        $dirctcustom_holiday->save();
        return redirect('/company/public/list-template')->with('templaytemsg', 'Added Successfully!');
    }
    public function edittemplate($id = 0)
    {
        $templateDetails = HolidayTemplateName::findOrFail($id);
        $scheduleHolidayData = DB::table('holidaytemplate_details')->select('*')->where('template_id',  $id)->get()->toArray();
        $scheduleHolidayDatacount = DB::table('holidaytemplate_details')->select('*')->where('template_id',  $id)->count();
        return view('account.company.publicholidaytemplate.edit')->with(compact(['templateDetails', 'scheduleHolidayData', 'scheduleHolidayDatacount']));
    }
    public function updatetemplate(Request $request, $id)
    {
        $this->validate($request, [
            'template_name'  => 'required',
        ]);
        HolidayTemplateName::where('id', $id)
            ->update([
                'name' => $request->input('template_name'),
            ]);
        $custom_date = $request->input('custom_date');
        if ($custom_date != "") {
            $note = $request->input('note');
            $time_hours_min = $request->input('time_hours_min');
            $time_hours_max = $request->input('time_hours_max');
            $final_custom_time = [];
            if (count($time_hours_min)) {
                for ($i = 0; $i < count($time_hours_min); $i++) {
                    $arr['open_time'] = $time_hours_min[$i];
                    $arr['close_time'] = $time_hours_max[$i];
                    $final_custom_time[] = $arr;
                }
            }
            $final_custom_time = json_encode($final_custom_time);
            $dirctcustom_holiday = new HolidayTemplateDetails;
            $dirctcustom_holiday->template_id = $id;
            $dirctcustom_holiday->custom_date = $custom_date;
            $dirctcustom_holiday->note = $note;
            $dirctcustom_holiday->custom_time = $final_custom_time;
            $dirctcustom_holiday->save();
        }
        return redirect('company/public/template-edit/' . $id)->with('msg', 'Template has been updated');
    }
    public function destroytemplate($id)
    {
        HolidayTemplateName::where('id', $id)->update(['deleted' => '1']);
        return redirect('/company/public/list-template')->with('msg', 'Deleted successfully');
    }
    public function deletePublicHolidayRecord(Request $request)
    {
        $data = HolidayTemplateDetails::find($request->id)->delete();
        return response()->json($data);
    }
    public function publicDataEdit($id = 0)
    {
        $scheduleHolidayData = DB::table('holidaytemplate_details')->select('*')->where('id',  $id)->get()->toArray();
        $scheduleHolidayDatacount = DB::table('holidaytemplate_details')->select('*')->where('id',  $id)->count();

        return view('account.company.publicholidaytemplate.dataedit')->with(compact(['scheduleHolidayData', 'scheduleHolidayDatacount']));
    }
    public function publicDataUpdate(Request $request, $id)
    {
        $template_id = $request->input('templateid');
        $time_hours_min = $request->input('time_hours_min');
        $time_hours_max = $request->input('time_hours_max');
        $final_custom_time = [];
        if (count($time_hours_min)) {
            for ($i = 0; $i < count($time_hours_min); $i++) {
                $arr['open_time'] = $time_hours_min[$i];
                $arr['close_time'] = $time_hours_max[$i];
                $final_custom_time[] = $arr;
            }
        }
        $final_custom_time = json_encode($final_custom_time);

        HolidayTemplateDetails::where('id', $id)
            ->update([
                'custom_date'             => $request->input('custom_date'),
                'note'                    => $request->input('note'),
                'custom_time'             => $final_custom_time
            ]);
        return redirect('company/public/holiday-template-dataedit/' . $template_id)->with('msg', 'Template has been updated');
    }
}
