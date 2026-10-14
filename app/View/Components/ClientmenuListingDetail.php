<?php

namespace App\View\Components;

use Illuminate\View\Component;
use DB;
use App\Helpers\Helper;
use App\Models\Currency;

class ClientmenuListingDetail extends Component
{
    
    public $mainDirectoryDetails;
    public function __construct($mainDirectoryDetails)
    {
        $this->mainDirectoryDetails = $mainDirectoryDetails;
    }
    public function render()
    {
        $language_id = Helper::getselectedLanguage();
        $getAllCurrency = Currency::select('*')->where('status', 'Active')->get()->toArray();
        $mainDirectoryD = $this->mainDirectoryDetails;
        
        return view('components.client.menu_detail',compact('getAllCurrency','mainDirectoryD'));
    }

}
