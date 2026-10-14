<?php

namespace App\View\Components;

use Illuminate\View\Component;
use DB;
use App\Helpers\Helper;
use App\Models\Currency;

class Clientmenu extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        
        $language_id = Helper::getselectedLanguage();
        $getAllCurrency = Currency::select('*')->where('status', 'Active')->get()->toArray();
       
        return view('components.client.menu',compact('getAllCurrency'));
       
    }
}
