<?php

namespace App\View\Components;

use Illuminate\View\Component;
use DB;
use App\Helpers\Helper;

class CClientfooter extends Component
{
   
    public function __construct()
    {
        //
    }

    public function render()
    {
        $data['footerData'] = DB::table('system_setting')->first();
        $data['socialfooterData'] = DB::table('frontend_setting')->first();
        return view('components.client.footervendor',['data' => $data]);
    }
}
