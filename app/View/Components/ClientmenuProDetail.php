<?php

namespace App\View\Components;

use Illuminate\View\Component;
use DB;
use App\Helpers\Helper;
use App\Models\Currency;
use App\Models\Frontendsetting;

class ClientmenuProDetail extends Component
{
    
    public $mainProductDetails;
    public function __construct($mainProductDetails)
    {
        $this->mainProductDetails = $mainProductDetails;
    }
    public function render()
    {
        $language_id = Helper::getselectedLanguage();
        $getAllCurrency = Currency::select('*')->where('status', 'Active')->get()->toArray();
        $mainProductD = $this->mainProductDetails;
        $headerData  = Frontendsetting::first(); 

        return view('components.client.menu_pro_detail',compact('getAllCurrency','mainProductD','headerData'));
    }

}
