<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = array(
            array('id' => 23,'name' => 'Claim' ,'slug' => "claim"),
            array('id' => 24,'name' => 'Shopping Card' ,'slug' => "shopping-card")
        );
        DB::table('modules')->insert($modules);
    }
}
