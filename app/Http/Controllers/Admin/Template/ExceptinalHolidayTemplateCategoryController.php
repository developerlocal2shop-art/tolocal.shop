<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\ExceptionalHolidayTemplateName;
use App\Models\ExceptionalHolidayTemplateDetails;
use App\Imports\ExceptionalHolidayImport;
use Maatwebsite\Excel\Facades\Excel;

class ExceptinalHolidayTemplateCategoryController extends Controller
{
    public function index(Request $request)
    {
        $templatedata = ExceptionalHolidayTemplateName::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.exceptionalholidaytemplate.index', ['templatedata' => $templatedata]);
    }
    public function create()
    {
        return view('admin.exceptionalholidaytemplate.create');
    }

    public function importExceptionalHolidayTemplate()
    {
        Excel::import(new ExceptionalHolidayImport,request()->file('file'));
        return back();
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'template_name'  => 'required',
        ]);
        $tempname = new ExceptionalHolidayTemplateName;
        $tempname->name     = $request->input('template_name');
        $tempname->created_by   = $request->input('createdby');
        $tempname->save();
        $templateid = $tempname->id;
        $custom_date = $request->input('custom_date');
        $note = $request->input('note');
        $day = $request->input('day');
        $closed = $request->input('closed');
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
        $dirctcustom_holiday->day = $day;
        $dirctcustom_holiday->closed = $closed;
        $dirctcustom_holiday->custom_time = $final_custom_time;
        $dirctcustom_holiday->save();
        return redirect('admin/exceptionalholiday-template')->with('msg', 'Added Successfully!');
    }
    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $templateDetails = ExceptionalHolidayTemplateName::findOrFail($id);
        $scheduleHolidayData = DB::table('exceptionalholidaytemplate_details')->select('*')->where('template_id',  $id)->get()->toArray();
        $scheduleHolidayDatacount = DB::table('exceptionalholidaytemplate_details')->select('*')->where('template_id',  $id)->count();

        return view('admin.exceptionalholidaytemplate.edit')->with(compact(['templateDetails', 'scheduleHolidayData', 'scheduleHolidayDatacount']));
    }
    public function update(Request $request, $id)
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
            $day = $request->input('day');
            $closed = $request->input('closed');
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
            $dirctcustom_holiday->day = $day;
            $dirctcustom_holiday->closed = $closed;
            $dirctcustom_holiday->custom_time = $final_custom_time;
            $dirctcustom_holiday->save();
        }
        return redirect('admin/exceptionalholiday-template-edit/' . $id)->with('msg', 'Template has been updated');
    }
    public function destroy($id)
    {
        ExceptionalHolidayTemplateName::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/exceptionalholiday-template')->with('msg', 'Deleted successfully');
    }
    public function deleteHolidayRecord(Request $request)
    {
        $data = ExceptionalHolidayTemplateDetails::find($request->id)->delete();
        return response()->json($data);
    }
}
