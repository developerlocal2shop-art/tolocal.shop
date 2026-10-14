<?php

namespace App\Http\Controllers\Account\HolidayTemplate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ExceptionalHolidayTemplateName;
use App\Models\ExceptionalHolidayTemplateDetails;

class ExceptionHolidayTemplateControllers extends Controller
{
    public function companyaddcustomTemplate()
    {
        if (Auth::check()) {
            if (Auth::user()->profile_status == 0) {
                return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
            } else {
                $templatedata = ExceptionalHolidayTemplateName::query()->where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                return view('account.company.exceptionholidaytemplate.index', ['templatedata' => $templatedata]);
            }
        } else {
            return abort(404);
        }
    }
    public function createtemplate()
    {
        return view('account.company.exceptionholidaytemplate.create');
    }
    public function submittemplate(Request $request)
    {
        $this->validate($request, [
            'template_name'  => 'required',
        ]);
        $tempname = new ExceptionalHolidayTemplateName;
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
        $dirctcustom_holiday = new ExceptionalHolidayTemplateDetails;
        $dirctcustom_holiday->template_id = $templateid;
        $dirctcustom_holiday->custom_date = $custom_date;
        $dirctcustom_holiday->note = $note;
        $dirctcustom_holiday->custom_time = $final_custom_time;
        $dirctcustom_holiday->save();
        return redirect('/company/exception/list-template')->with('templaytemsg', 'Added Successfully!');
    }
    public function edittemplate($id = 0)
    {
        $templateDetails = ExceptionalHolidayTemplateName::findOrFail($id);
        $scheduleHolidayData = DB::table('exceptionalholidaytemplate_details')->select('*')->where('template_id',  $id)->get()->toArray();
        $scheduleHolidayDatacount = DB::table('exceptionalholidaytemplate_details')->select('*')->where('template_id',  $id)->count();

        return view('account.company.exceptionholidaytemplate.edit')->with(compact(['templateDetails', 'scheduleHolidayData', 'scheduleHolidayDatacount']));
    }
    public function updatetemplate(Request $request, $id)
    {
        $this->validate($request, [
            'template_name'  => 'required',
        ]);
        ExceptionalHolidayTemplateName::where('id', $id)
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
            $dirctcustom_holiday = new ExceptionalHolidayTemplateDetails;
            $dirctcustom_holiday->template_id = $id;
            $dirctcustom_holiday->custom_date = $custom_date;
            $dirctcustom_holiday->note = $note;
            $dirctcustom_holiday->custom_time = $final_custom_time;
            $dirctcustom_holiday->save();
        }
        return redirect('company/exception/template-edit/' . $id)->with('msg', 'Template has been updated');
    }
    public function destroytemplate($id)
    {
        ExceptionalHolidayTemplateName::where('id', $id)->update(['deleted' => '1']);
        return redirect('/company/exception/list-template')->with('msg', 'Deleted successfully');
    }
    public function deleteHolidayRecord(Request $request)
    {
        $data = ExceptionalHolidayTemplateDetails::find($request->id)->delete();
        return response()->json($data);
    }
    public function DataEdit($id = 0)
    {
        $scheduleHolidayData = DB::table('exceptionalholidaytemplate_details')->select('*')->where('id',  $id)->get()->toArray();
        $scheduleHolidayDatacount = DB::table('exceptionalholidaytemplate_details')->select('*')->where('id',  $id)->count();

        return view('account.company.exceptionholidaytemplate.dataedit')->with(compact(['scheduleHolidayData', 'scheduleHolidayDatacount']));
    }
    public function DataUpdate(Request $request, $id)
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

        ExceptionalHolidayTemplateDetails::where('id', $id)
            ->update([
                'custom_date'             => $request->input('custom_date'),
                'note'                    => $request->input('note'),
                'custom_time'             => $final_custom_time
            ]);
        return redirect('company/exception/holiday-template-dataedit/' . $template_id)->with('msg', 'Template has been updated');
    }
}
