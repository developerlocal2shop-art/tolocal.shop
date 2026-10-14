<?php

namespace App\View\Components;
use Illuminate\View\Component;
use App\Models\Frontendsetting;

class Clientheader extends Component
{
   
    public function __construct()
    {
        //
    }

    public function render()
    {
        $data['headerData']           = Frontendsetting::first(); 
        return view('components.client.header',['data' => $data]);
    }
}
