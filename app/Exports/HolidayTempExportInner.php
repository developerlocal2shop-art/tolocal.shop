<?php

namespace App\Exports;

use App\Models\HolidayTemplateDetails;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HolidayTempExportInner implements FromCollection , WithHeadings, WithMapping
{
    

    protected $template_id;

    function __construct($data) {
            $this->template_id = $data['template_id'];
    }

    public function collection()
    {
        $template_id = $this->template_id;
        $prodata = HolidayTemplateDetails::where('template_id', $template_id)->get([
            'custom_date',
            'note',
            'day',
            'custom_time',
            'closed',
        ]);     
        return $prodata;
    }

    public function map($row): array
    {
        // Decode the JSON in the custom_time field
        $customTime = json_decode($row->custom_time, true);
        if($row->closed == 1 || $row->closed == 'Yes'){
            $closed = 'Yes';
        }else{
            $closed = 'No';
        }

        return [
            $row->custom_date,
            $row->note,
            $row->day,
            $customTime[0]['open_time'] ?? 'N/A', // Map Open Time
            $customTime[0]['close_time'] ?? 'N/A', // Map Close Time
            $closed, // Convert closed to Yes/No
        ];
    }

    public function headings() : array
    {
        return [
            'Date',
            'Holiday',
            'Day',
            'Open Time',
            'Close Time',
            'Closed',
        ]; 
    } 

}
