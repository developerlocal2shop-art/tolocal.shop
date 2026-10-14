<?php

namespace App\Http\Controllers\Account\HolidayTemplate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Normaltemplate;
use App\Models\TemplateName;
use App\Helpers\Helper;

class NormalHolidayTemplateControllers extends Controller
{
    public function companyaddcustomTemplate()
    {
        if (Auth::check()) {
            if (Auth::user()->profile_status == 0) {
                return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
            } else {
                $templatedata = TemplateName::query()->where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                return view('account.company.normalholidaytemplate.index', ['templatedata' => $templatedata]);
            }
        } else {
            return abort(404);
        }
    }
    public function createtemplate()
    {
        return view('account.company.normalholidaytemplate.create');
    }
    public function submittemplate(Request $request)
    {
        $this->validate($request, [
            'template_name'  => 'required',
        ]);
        $tempname                   = new TemplateName;
        $tempname->name             = $request->input('template_name');
        $tempname->directory_id     = $request->input('directoryId');
        $tempname->created_by       = $request->input('createdby');
        $tempname->save();
        $templateid = $tempname->id;
        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
        foreach ($days as $key => $value) {
            $count = count($request->input($value . "_hours_min"));
            $minHours = $request->input($value . "_hours_min");
            $maxHours = $request->input($value . "_hours_max");
            for ($i = 0; $i < $count; $i++) {
                $dirctschedual = new Normaltemplate;
                $dirctschedual->template_id     = $templateid;
                $dirctschedual->day             = $value;
                $dirctschedual->is_closed       = $request->input($value . "_is_closed") ? '1' : '0';
                $dirctschedual->open_time       = $minHours[$i];
                $dirctschedual->close_time      = $maxHours[$i];
                $dirctschedual->save();
            }
        }
        return redirect('/company/normal/list-template')->with('msg', 'Added Successfully!');
    }
    public function edittemplate($id = 0)
    {
        $templateDetails = TemplateName::findOrFail($id);
        $data['scheduleData'] = DB::table('normaltemplate')->select('*')->where('template_id',  $id)->get()->toArray();
        $scheduleData = self::group_assoc(json_decode(json_encode($data['scheduleData']), 1), 'day');
        return view('account.company.normalholidaytemplate.edit')->with(compact(['templateDetails', 'scheduleData']));
    }
    function group_assoc($array, $key)
    {
        $return = array();
        foreach ($array as $v) {
            $return[$v[$key]][] = $v;
        }
        return $return;
    }
    public function updatetemplate(Request $request, $id)
    {
        $this->validate($request, [
            'template_name'  => 'required',
        ]);
        TemplateName::where('id', $id)
            ->update([
                'name' => $request->input('template_name'),
            ]);
        Normaltemplate::where('template_id', $id)->delete();
        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
        foreach ($days as $key => $value) {
            $count = count($request->input($value . "_hours_min"));
            $minHours = $request->input($value . "_hours_min");
            $maxHours = $request->input($value . "_hours_max");
            for ($i = 0; $i < $count; $i++) {
                $templatetime = new Normaltemplate;
                $templatetime->template_id    = $id;
                $templatetime->day             = $value;
                $templatetime->is_closed       = $request->input($value . "_is_closed") ? '1' : '0';
                $templatetime->open_time       = $minHours[$i];
                $templatetime->close_time      = $maxHours[$i];
                $templatetime->save();
            }
        }
        return redirect('company/normal/template-edit/' . $id)->with('msg', 'Template has been updated');
    }
    public function destroytemplate($id)
    {
        TemplateName::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/normal/normal-template')->with('msg', 'Deleted successfully');
    }
}
