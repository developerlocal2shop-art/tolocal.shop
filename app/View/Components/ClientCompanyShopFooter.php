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
        return view('components.client.footercompanydetailshop',['data' => $data]);
    }
}
