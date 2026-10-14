<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\DirectoryCustomHoliday;
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

class VendorPublicHolidayImport implements ToCollection, WithHeadingRow
{

    protected $directoryId;

    public function __construct($directoryId)
    {
        $this->directoryId = $directoryId;
    }

    public function collection(Collection $rows)
    {
        try {

            $directoryId = $this->directoryId;
            //HolidayTemplateDetails::where('template_id',$templateId)->delete();
            $i = 1;
            foreach ($rows as $row) {

                //dd($row);
                // $tempname = new HolidayTemplateName;  // $tempname->name     = $row['template_name']??''; // $tempname->created_by   = 0;// $tempname->save();
                $directoryId = $directoryId;
                $custom_date = $row['date']??'';
                $note = $row['holiday']??'';
                $day = $row['day']??'';
                $closed = $row['closed']??'';

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
                $dirctcustom_holiday = new DirectoryCustomHoliday();
                $dirctcustom_holiday->directory_id = $directoryId;
                $dirctcustom_holiday->custom_date = $custom_date;
                $dirctcustom_holiday->closed = $closed;
                $dirctcustom_holiday->note = $note;
                $dirctcustom_holiday->day = $day;
                $dirctcustom_holiday->custom_time = $final_custom_time;
                $dirctcustom_holiday->save();

            }

            return redirect('admin/holiday-template')->with('msg', 'Added Successfully!');

        } catch (\Exception $e) {
            return back()->with('error', "Somthing went wrong. Some prodcts not uploaded");
        }

    }

}
