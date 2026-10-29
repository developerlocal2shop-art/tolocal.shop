<?php

namespace App\View\Components;

use Illuminate\View\Component;
use DB;
use App\Helpers\Helper;

class ClientCompanyShopFooter extends Component
{

    protected $mainDirectoryDetails;
    public function __construct($mainDirectoryDetails)
    {
        $this->mainDirectoryDetails = $mainDirectoryDetails;
    }

    public function render()
    {
        $data['footerData'] = DB::table('system_setting')->first();
        $data['socialfooterData'] = DB::table('frontend_setting')->first();
        $data['mainDirectoryDetails'] = $this->mainDirectoryDetails;
        $directoryId = $this->mainDirectoryDetails->id ?? 0;
        $schedules = DB::table('directory_schedule')
            ->where('directory_id', $directoryId)
            ->orderBy('id')
            ->get()
            ->groupBy(function ($schedule) {
                return strtolower($schedule->day ?? '');
            });

        $dayLabels = [
            'monday' => 'Måndag',
            'tuesday' => 'Tisdag',
            'wednesday' => 'Onsdag',
            'thursday' => 'Torsdag',
            'friday' => 'Fredag',
            'saturday' => 'Lördag',
            'sunday' => 'Söndag',
        ];
        $dayHours = [];

        foreach ($dayLabels as $day => $label) {
            $daySchedules = $schedules->get($day, collect());

            if ($daySchedules->isEmpty()) {
                $dayHours[$day] = null;
                continue;
            }

            $openSchedules = $daySchedules->filter(function ($schedule) {
                return !in_array(strtolower((string) $schedule->is_closed), ['1', 'yes', 'true'], true);
            });

            if ($openSchedules->isEmpty()) {
                $dayHours[$day] = 'Stängt';
                continue;
            }

            $timeRanges = $openSchedules->map(function ($schedule) {
                $openTime = trim((string) $schedule->open_time);
                $closeTime = trim((string) $schedule->close_time);

                if ($openTime === '' || $closeTime === '') {
                    return null;
                }

                return $openTime . ' - ' . $closeTime;
            })->filter()->unique()->values();

            $dayHours[$day] = $timeRanges->isEmpty() ? 'Ej angivet' : $timeRanges->implode(', ');
        }

        $weekdayHours = array_intersect_key($dayHours, array_flip(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']));
        if (count(array_filter($weekdayHours, function ($hours) {
            return $hours !== null;
        })) === count($weekdayHours)
            && count(array_unique($weekdayHours)) === 1
        ) {
            $data['openingHours'] = [['label' => 'Vardagar', 'value' => reset($weekdayHours)]];
        } else {
            $data['openingHours'] = [];
            foreach ($weekdayHours as $day => $hours) {
                if ($hours !== null) {
                    $data['openingHours'][] = ['label' => $dayLabels[$day], 'value' => $hours];
                }
            }
        }

        foreach (['saturday', 'sunday'] as $day) {
            if ($dayHours[$day] !== null) {
                $data['openingHours'][] = ['label' => $dayLabels[$day], 'value' => $dayHours[$day]];
            }
        }

        if (empty($data['openingHours'])) {
            $data['openingHours'][] = ['label' => 'Öppettider', 'value' => 'Ej angivet'];
        }

        return view('components.client.footercompanydetailshop',['data' => $data]);
    }
}
