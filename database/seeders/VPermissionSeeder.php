<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('v_permissions')->delete();
        $permissions = array(
            array('id' => 1,'name' => 'Add' ,'slug' => "add"),
            array('id' => 2,'name' => 'Edit' ,'slug' => "edit"),
            array('id' => 3,'name' => 'Delete' ,'slug' => "delete"),
            array('id' => 4,'name' => 'Update' ,'slug' => "update"),
            array('id' => 5,'name' => 'View' ,'slug' => "view"),
            array('id' => 6,'name' => 'Status' ,'slug' => "status"),
            array('id' => 7,'name' => 'Download' ,'slug' => "download")
        );
        DB::table('v_permissions')->insert($permissions);
    }
}
