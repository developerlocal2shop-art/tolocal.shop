<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\HolidayTemplateName;
use App\Models\TemplateName;
use App\Models\HolidayTemplateDetails;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class HolidayTempImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        try {

            //dd($rows);

            $i = 1;
            foreach ($rows as $row) {

                $tempname = new HolidayTemplateName;
                $tempname->name     = $row['template_name']??'';
                $tempname->created_by   = 0;
                $tempname->save();
                $templateid = $tempname->id;

                $custom_date = $row['date']??'';
                $note = $row['note']??'';

                $time_hours_min = explode(',', $row['open_time']??'');//$request->input('time_hours_min');
                $time_hours_max = explode(',', $row['close_time']??'');//$request->input('time_hours_max');
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

            }

            return redirect('admin/holiday-template')->with('msg', 'Added Successfully!');

        } catch (\Exception $e) {
            return back()->with('error', "Somthing went wrong. Some prodcts not uploaded");
        }

    }

}
