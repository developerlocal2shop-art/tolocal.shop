<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\Normaltemplate;
use App\Models\TemplateName;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class NormalTempImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        try {

            $i = 1;
            foreach ($rows as $row) {

                $tempname = new TemplateName;
                $tempname->name     = $row['template_name']??'';
                $tempname->directory_id   = 0;
                $tempname->created_by   = 0;
                $tempname->save();
                $templateid = $tempname->id;

                $is_closed = explode(',', $row['is_closed']??'');
                $open_time = explode(',', $row['open_time']??'');
                $close_time = explode(',', $row['close_time']??'');
                $days = explode(',', $row['day']??'');
                foreach ($days as $key => $value) {
                        $dirctschedual = new Normaltemplate;
                        $dirctschedual->template_id     = $templateid;
                        $dirctschedual->day             = $value;
                        $dirctschedual->is_closed       = $is_closed[$key];
                        $dirctschedual->open_time       = $open_time[$key];
                        $dirctschedual->close_time      = $close_time[$key];
                        $dirctschedual->save();
                }
            }

            return redirect('admin/normal-template')->with('msg', 'Added Successfully!');
			
        } catch (\Exception $e) {
            return back()->with('error', "Somthing went wrong. Some prodcts not uploaded");
        }

    }

}
