<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_statuses')->delete();
        $order_statuses = array(
            array('id' => 1,'name' => 'Pending' ,'slug' => "pending",'active' => '1'),
            array('id' => 2,'name' => 'Packed' ,'slug' => "packed",'active' => '1'),
            array('id' => 3,'name' => 'Shipped' ,'slug' => "shipped",'active' => '1'),
            array('id' => 4,'name' => 'Payment Cancelled' ,'slug' => "payment-cancelled",'active' => '1'),
            array('id' => 5,'name' => 'Delivered' ,'slug' => "delivered",'active' => '1'),
            array('id' => 6,'name' => 'RTO' ,'slug' => "rto",'active' => '1'),
            array('id' => 7,'name' => 'Refunded' ,'slug' => "refunded",'active' => '1')
        );
        DB::table('order_statuses')->insert($order_statuses);
    }
}
