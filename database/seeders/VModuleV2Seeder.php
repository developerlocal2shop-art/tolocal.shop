<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VModuleV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = array(
            array('id' => 14,'name' => 'Offline Reports' ,'slug' => "offline-reports"),
            array('id' => 15,'name' => 'Trademarks' ,'slug' => "trademarks"),
            array('id' => 16,'name' => 'Shopping Card' ,'slug' => "shopping-card"),
            array('id' => 17,'name' => 'Blogs' ,'slug' => "blogs"),
            array('id' => 18,'name' => 'Events' ,'slug' => "events"),
            array('id' => 19,'name' => 'Advertisement' ,'slug' => "advertisement")
        );
        DB::table('v_modules')->insert($modules);
    }
}
