<?php

namespace App\Exports;

use App\Helpers\Helper;
use App\Models\Advertisement;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompetitionAnswerExportVendor implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {

        $companyId = auth()->user()->directory_id;
        
        $advertisementDetails = Advertisement::with([
            'questions' => function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            }
        ])->findOrFail($this->id);

        $exportData = [];
        $sn = 1;

        foreach ($advertisementDetails->questions as $row) {

            $getCompanyName = Helper::getDirectoryIdSlug($row->company_id);
            $questionsAns = Helper::QuestionsAnsById($row->id);

            // If no answers found
            if ($questionsAns->answers->count() == 0) {
                $exportData[] = [
                    'sn'             => $sn,
                    'question'       => $row->question_text,
                    'answer'         => '',
                    'customer'       => '',
                    'email'          => '',
                    'company'        => $getCompanyName->title ?? 'Unknown Company',
                    'winner'         => '',
                    'question_date'  => date('d M Y', strtotime($row->created_at)),
                    'answer_date'    => '',
                ];
                $sn++;
                continue;
            }
            // Answers
            foreach ($questionsAns->answers as $answers_val) {

                $getUser = Helper::getUserById($answers_val->customer_id);
                $exportData[] = [
                    'sn'             => $sn,
                    'question'       => $row->question_text,
                    'answer'         => $answers_val->answer ?? '',
                    'customer'       => $getUser->name.' '.$getUser->last_name ?? '',
                    'email'          => $getUser->email ?? '',
                    'company'        => $getCompanyName->title ?? 'Unknown Company',
                    'winner'         => $answers_val->is_correct == 1 ? 'Yes' : 'No',
                    'question_date'  => date('d M Y', strtotime($row->created_at)),
                    'answer_date'    => date('d M Y', strtotime($answers_val->created_at)),
                ];
            }

            $sn++;
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'SN',
            'Question',
            'Answer',
            'Customer Name',
            'Customer Email',
            'Posted By Company',
            'Winner',
            'Question Created',
            'Answer Created',
        ];
    }
}