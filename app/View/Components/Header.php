<?php

namespace App\View\Components;

use Illuminate\View\Component;
use DB;

class Header extends Component
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
        $adminDetail        = DB::table('admin')->select("*")->where('id',1)->first();
        return view('components.header',compact('adminDetail'));
    }
}
