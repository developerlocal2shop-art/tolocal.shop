<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->delete();
        $modules = array(
            array('id' => 1,'name' => 'Dashboard' ,'slug' => "dashboard"),
            array('id' => 2,'name' => 'Reports' ,'slug' => "reports"),
            array('id' => 3,'name' => 'Amenities' ,'slug' => "amenities"),
            array('id' => 4,'name' => 'Categories' ,'slug' => "categories"),
            array('id' => 5,'name' => 'Tags' ,'slug' => "tags"),
            array('id' => 6,'name' => 'Directory' ,'slug' => "directory"),
            array('id' => 7,'name' => 'Trademarks' ,'slug' => "trademarks"),
            array('id' => 8,'name' => 'Products' ,'slug' => "products"),
            array('id' => 9,'name' => 'Product Offer' ,'slug' => "product_offer"),
            array('id' => 10,'name' => 'Templates' ,'slug' => "templates"),
            array('id' => 11,'name' => 'Orders' ,'slug' => "orders"),
            array('id' => 12,'name' => 'Reviews' ,'slug' => "reviews"),
            array('id' => 13,'name' => 'Discount' ,'slug' => "discount"),
            array('id' => 14,'name' => 'Appointment booking' ,'slug' => "appointment_booking"),
            array('id' => 15,'name' => 'Blogs' ,'slug' => "blogs"),
            array('id' => 16,'name' => 'Events' ,'slug' => "events"),
            array('id' => 17,'name' => 'Competition' ,'slug' => "competition"),
            array('id' => 18,'name' => 'Users' ,'slug' => "users"),
            array('id' => 19,'name' => 'Permission managment' ,'slug' => "permission_managment"),
            array('id' => 20,'name' => 'Languages' ,'slug' => "languages"),
            array('id' => 21,'name' => 'Settings' ,'slug' => "settings"),
            array('id' => 22,'name' => 'Mail' ,'slug' => "mail")
        );
        DB::table('modules')->insert($modules);
    }
}
