<?php

namespace App\Http\Controllers\Admin\Template;

use App\Exports\HolidayTempExportInner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\HolidayTemplateName;
use App\Models\HolidayTemplateDetails;
use App\Imports\HolidayTempImport;
use App\Imports\HolidayTempImportInner;
use Maatwebsite\Excel\Facades\Excel;

class HolidayTemplateCategoryController extends Controller
{
    public function index(Request $request)
    {
        $templatedata = HolidayTemplateName::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.holidaytemplate.index', ['templatedata' => $templatedata]);
    }
    public function create()
    {
        return view('admin.holidaytemplate.create');
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'template_name'  => 'required',
        ]);
        $tempname = new HolidayTemplateName;
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
        $dirctcustom_holiday = new HolidayTemplateDetails;
        $dirctcustom_holiday->template_id = $templateid;
        $dirctcustom_holiday->custom_date = $custom_date;
        $dirctcustom_holiday->note = $note;
        $dirctcustom_holiday->day = $day;
        $dirctcustom_holiday->closed = $closed;
        $dirctcustom_holiday->custom_time = $final_custom_time;
        $dirctcustom_holiday->save();

        return redirect('admin/holiday-template')->with('msg', 'Added Successfully!');
    }

    public function importHolidayTemp()
    {
        Excel::import(new HolidayTempImport,request()->file('file'));
        return back();
    }

    public function importHolidayTempInner(Request $request)
    {
        // $files = [request()->file('file'), $request->template_id];
        // Excel::import(new HolidayTempImportInner,$files);
        $file = $request->file('file'); // Get the uploaded file
        $templateId = $request->template_id; // Retrieve the template ID
        Excel::import(new HolidayTempImportInner($templateId), $file);

        return back();
    }

    public function exportPublicHolidayTempInner(Request $request)
    {
        $fileName = 'public_holiday_template_' . $request->template_id . '.xlsx';
        return Excel::download(new HolidayTempExportInner($request->all()), $fileName);
    }

    public function edit($id = 0)
    {
        $templateDetails = HolidayTemplateName::findOrFail($id);
        $scheduleHolidayData = DB::table('holidaytemplate_details')->select('*')->where('template_id',  $id)->get()->toArray();
        $scheduleHolidayDatacount = DB::table('holidaytemplate_details')->select('*')->where('template_id',  $id)->count();
        return view('admin.holidaytemplate.edit')->with(compact(['templateDetails', 'scheduleHolidayData', 'scheduleHolidayDatacount']));
    }
    public function update(Request $request, $id)
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
            $dirctcustom_holiday = new HolidayTemplateDetails;
            $dirctcustom_holiday->template_id = $id;
            $dirctcustom_holiday->custom_date = $custom_date;
            $dirctcustom_holiday->closed = $closed;
            $dirctcustom_holiday->note = $note;
            $dirctcustom_holiday->day = $day;
            $dirctcustom_holiday->custom_time = $final_custom_time;
            $dirctcustom_holiday->save();
        }
        return redirect('admin/holiday-template-edit/' . $id)->with('msg', 'Template has been updated');
    }
    public function destroy($id)
    {
        HolidayTemplateName::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/holiday-template')->with('msg', 'Deleted successfully');
    }
    public function deleteHolidayRecord(Request $request)
    {
        $data = HolidayTemplateDetails::find($request->id)->delete();
        return response()->json($data);
    }
    public function dataedit($id = 0)
    {
        $scheduleHolidayData = DB::table('holidaytemplate_details')->select('*')->where('id',  $id)->get()->toArray();
        $scheduleHolidayDatacount = DB::table('holidaytemplate_details')->select('*')->where('id',  $id)->count();

        return view('admin.holidaytemplate.dataedit')->with(compact(['scheduleHolidayData', 'scheduleHolidayDatacount']));
    }
    public function dataupdate(Request $request, $id)
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
                'closed'                  => $request->input('closed'),
                'note'                    => $request->input('note'),
                'day'                     => $request->input('day'),
                'custom_time'             => $final_custom_time
            ]);
        return redirect('admin/holiday-template-edit/' . $template_id)->with('msg', 'Template has been updated');
    }
}
