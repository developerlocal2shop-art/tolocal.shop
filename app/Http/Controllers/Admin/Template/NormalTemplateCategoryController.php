<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Normaltemplate;
use App\Models\TemplateName;
use App\Imports\NormalTempImport;
use Maatwebsite\Excel\Facades\Excel;

class NormalTemplateCategoryController extends Controller
{
    public function index(Request $request)
    {
        $templatedata = TemplateName::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.normaltemplate.index', ['templatedata' => $templatedata]);
    }
    public function create()
    {
        return view('admin.normaltemplate.create');
    }
    public function submit(Request $request)
    {

        //dd($request->all());

        $this->validate($request, [
            'template_name'  => 'required',
        ]);
        $tempname = new TemplateName;
        $tempname->name     = $request->input('template_name');
        $tempname->created_by   = $request->input('createdby');
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
        return redirect('admin/normal-template')->with('msg', 'Added Successfully!');
    }

    public function importNormalTemp()
    {
        Excel::import(new NormalTempImport,request()->file('file'));
        return back();
    }

    public function edit($id = 0)
    {
        $templateDetails = TemplateName::findOrFail($id);
        $data['scheduleData'] = DB::table('normaltemplate')->select('*')->where('template_id',  $id)->get()->toArray();
        $scheduleData = self::group_assoc(json_decode(json_encode($data['scheduleData']), 1), 'day');
        return view('admin.normaltemplate.edit')->with(compact(['templateDetails', 'scheduleData']));
    }
    function group_assoc($array, $key)
    {
        $return = array();
        foreach ($array as $v) {
            $return[$v[$key]][] = $v;
        }
        return $return;
    }
    public function update(Request $request, $id)
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
        return redirect('admin/normal-template-edit/' . $id)->with('msg', 'Template has been updated');
    }
    public function destroy($id)
    {
        TemplateName::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/normal-template')->with('msg', 'Deleted successfully');
    }
}
