<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('v_modules')->delete();
        $modules = array(
            array('id' => 1,'name' => 'Reports' ,'slug' => "reports"),
            array('id' => 2,'name' => 'Company Info' ,'slug' => "company-info"),
            array('id' => 3,'name' => 'Products' ,'slug' => "products"),
            array('id' => 4,'name' => 'Offers' ,'slug' => "offers"),
            array('id' => 5,'name' => 'Orders' ,'slug' => "orders"),
            array('id' => 6,'name' => 'Directory Own Tags' ,'slug' => "directory-own-tags"),
            array('id' => 7,'name' => 'Coupons' ,'slug' => "coupons"),
            array('id' => 8,'name' => 'Gift voucher' ,'slug' => "gift-voucher"),
            array('id' => 9,'name' => 'Products discount' ,'slug' => "products-discount"),
            array('id' => 10,'name' => 'Booking' ,'slug' => "booking"),
            array('id' => 11,'name' => 'Permission' ,'slug' => "permission"),
            array('id' => 12,'name' => 'Role' ,'slug' => "role"),
            array('id' => 13,'name' => 'Mail' ,'slug' => "mail")
        );
        DB::table('v_modules')->insert($modules);
    }
}
