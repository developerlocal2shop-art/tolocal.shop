<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	DB::table('currency')->delete();
	$currencies = array(
		array('id' => 1,'name' => 'Swedish kronor' ,'code' => "SEK",'symbol' => "kr",'base_currency' => "yes",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 2,'name' => 'Pound2' ,'code' => "GBP",'symbol' => "£",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 3,'name' => 'Polen' ,'code' => "PLN",'symbol' => "gr",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 4,'name' => 'Danmark' ,'code' => "DKK",'symbol' => "kr",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 5,'name' => 'Norwegian' ,'code' => "NOK",'symbol' => "kr",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 6,'name' => 'Euro' ,'code' => "EUR",'symbol' => "€",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 7,'name' => 'Bulgarien' ,'code' => "BGN",'symbol' => "лв",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 8,'name' => 'Kroatien' ,'code' => "HRK",'symbol' => "kn",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 9,'name' => 'Rumänien' ,'code' => "RON",'symbol' => "lei",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 10,'name' => 'Tjeckien' ,'code' => "CZK",'symbol' => "Kč",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
		array('id' => 11,'name' => 'Ungern' ,'code' => "HUF",'symbol' => "Ft",'base_currency' => "no",'status' => "Active",'created_at' => "2021-11-15 23:49:19",'updated_at' => "2024-04-29 06:23:26"),
	);
	DB::table('currency')->insert($currencies);
    }
}
