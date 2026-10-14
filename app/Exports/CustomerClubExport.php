<?php

namespace App\Exports;

use App\Helpers\Helper;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerClubExport implements FromCollection, WithHeadings, WithMapping
{
    protected $customers;
    protected $index = 0;

    public function __construct(Collection $customers)
    {
        $this->customers = $customers;
    }

    public function collection()
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Name',
            'Phone',
            'Email',
            'City',
            'State',
            'Country',
            'Zipcode',
            'Message',
            'Join Date',
            'Status'
        ];
    }

    public function map($row): array
    {
        $this->index++;

        $city = '';
        if (is_numeric($row->city)) {
            $cityObj = Helper::getCityName($row->city);
            $city = $cityObj && isset($cityObj->name) ? $cityObj->name : '';
        } else {
            $city = $row->city;
        }

        $stateObj = Helper::getStateName($row->state);
        $countryObj = Helper::getCountryName($row->country);
        $status = ($row->opt_out == '1') ? 'IN' : 'OUT';

        return [
            $this->index,
            $row->fname.' '.$row->lname,
            $row->phone,
            $row->email,
            $city,
            $stateObj->name ?? '',
            $countryObj->name ?? '',
            $row->zipcode,
            $row->message,
            $row->joined_at,
            $status,
        ];
    }
}
