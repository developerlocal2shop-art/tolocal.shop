<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\HolidayTemplateName;
use App\Models\TemplateName;
use App\Models\HolidayTemplateDetails;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
//use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class HolidayTempImportInner implements ToCollection, WithHeadingRow
{

    protected $templateId;

    public function __construct($templateId)
    {
        $this->templateId = $templateId;
    }

    public function collection(Collection $rows)
    {
        try {

            $templateId = $this->templateId;

            foreach ($rows as $row) {

                $custom_date = null;
                if (!empty($row['date'])) {
                    if (is_numeric($row['date'])) {
                        $custom_date = date('Y-m-d', Date::excelToTimestamp($row['date']));
                    } else {
                        try {
                            $custom_date = Carbon::parse($row['date'])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $custom_date = null; // fallback if invalid
                        }
                    }
                }

                //dd($custom_date);

                $openTime = '00:00';
                $closeTime = '00:00';
                if (!empty($row['open_time'])) {
                    if (is_numeric($row['open_time'])) {
                        $openTime = gmdate('H:i', $row['open_time'] * 86400);
                    } else {
                        $openTime = date('H:i', strtotime($row['open_time']));
                    }
                }

                if (!empty($row['close_time'])) {
                    if (is_numeric($row['close_time'])) {
                        $closeTime = gmdate('H:i', $row['close_time'] * 86400);
                    } else {
                        $closeTime = date('H:i', strtotime($row['close_time']));
                    }
                }

                $customTime = [
                    [
                        'open_time'  => $openTime,
                        'close_time' => $closeTime,
                    ]
                ];

                $day = $row['day']??'';
                $closed = $row['closed']??'';

                HolidayTemplateDetails::create([
                    'template_id' => $templateId,
                    'custom_date' => $custom_date,
                    'note'        => $row['holiday'] ?? '',
                    'day'         => $day,
                    'closed'      => $closed,
                    'custom_time' => json_encode($customTime),
                ]);
            }

            return redirect('admin/holiday-template')->with('msg', 'Added Successfully!');

        } catch (\Exception $e) {
            return back()->with('error', "Somthing went wrong. Some prodcts not uploaded");
        }

    }

}
