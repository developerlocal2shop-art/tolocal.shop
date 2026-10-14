<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageModules extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('package_modules')->delete();
        $package_modules = array(
            array('id' => 1, 'name' => 'Reports' ,         'slug' => "reports" ,'parent_id' => 0 ,'status' => "Active" ,'deleted' => "0"),
			array('id' => 2, 'name' => 'Company Info' ,    'slug' => "company-info" ,'parent_id' => 0 ,'status' => "Active",'deleted' => "0"),
			array('id' => 3, 'name' => 'Basic' ,           'slug' => "basic" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 4, 'name' => 'Location' ,        'slug' => "location" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 5, 'name' => 'Type' ,            'slug' => "type" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 6, 'name' => 'Type More Link' ,  'slug' => "type-more-link" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 7, 'name' => 'Amenities' ,       'slug' => "amenities" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 8, 'name' => 'Media' ,           'slug' => "media" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 9, 'name' => 'Listing Thumbnail and Listing Cover' ,'slug' => "listing-thumbnail-and-listing-cover" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 10,'name' => 'SEO' ,            'slug' => "seo" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 11,'name' => 'Schedule normal', 'slug' => "schedule-normal" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 12,'name' => 'Schedule all' ,   'slug' => "schedule-all" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 13,'name' => 'Links' ,          'slug' => "links" ,'parent_id' => 2 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 14,'name' => 'Claim' ,          'slug' => "claim" ,'parent_id' => 2 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 15,'name' => 'Category' ,       'slug' => "category" ,'parent_id' => 2 ,'status' => "Active",'deleted' => "0"),
			array('id' => 16,'name' => 'Sub Category' ,   'slug' => "sub-category" ,'parent_id' => 2 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 17,'name' => 'Tags' ,           'slug' => "tags" ,'parent_id' => 2 ,'status' => "Active", 'deleted' => "0"),
			
			array('id' => 18,'name' => 'Products' ,       'slug' => "products" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 19,'name' => 'Import' ,         'slug' => "import" ,'parent_id' => 18 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 20,'name' => 'Product Video',   'slug' => "product-video" ,'parent_id' => 18 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 21,'name' => 'Offers' ,         'slug' => "offers" ,'parent_id' => 18 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 22,'name' => 'Orders' ,         'slug' => "orders" ,'parent_id' => 18 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 23,'name' => 'Coupons' ,        'slug' => "coupons" ,'parent_id' => 18 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 24,'name' => 'Gift Voucher' ,   'slug' => "gift-voucher" ,'parent_id' => 18 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 25,'name' => 'Free Products Discount' ,'slug' => "free-products-discount" ,'parent_id' => 18 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 26,'name' => 'Shopping Card' ,'slug' => "shopping-card" ,'parent_id' => 18 ,'status' => "Active", 'deleted' => "0"),
			
			array('id' => 27,'name' => 'Booking' ,               'slug' => "booking" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
		    array('id' => 28,'name' => 'Mail' ,                  'slug' => "mail" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 29,'name' => 'Permission' ,            'slug' => "permission" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 30,'name' => 'Role' ,                  'slug' => "role" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 31,'name' => 'Offline Reports' , 'slug' => "offline-reports" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 32,'name' => 'Trademarks' ,'slug' => "trademarks" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 33,'name' => 'Profile' ,'slug' => "profile" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 34,'name' => 'Events' ,'slug' => "events" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 35,'name' => 'Blog' ,'slug' => "blog" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 36,'name' => 'Advertisment' ,'slug' => "advertisment" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			array('id' => 37,'name' => 'Customer Club' ,'slug' => "customer-club" ,'parent_id' => 0 ,'status' => "Active", 'deleted' => "0"),
			
		);
        DB::table('package_modules')->insert($package_modules);
    }
}
