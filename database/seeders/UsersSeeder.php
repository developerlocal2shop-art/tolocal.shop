<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use App\Models\VModule;
use App\Models\VPermission;
use App\Models\VRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //DB::table('users')->delete();
        //DB::table('v_roles')->delete();
        
        // $dev_permission = VPermission::where('slug','edit')->first();
		// $manager_permission = VPermission::where('slug', 'view')->first();
	   
		// $dev_role = new VRole();
        // $dev_role->id = 1;
		// $dev_role->slug = 'vendor';
		// $dev_role->name = 'Vendor';
		// $dev_role->save();
		// $dev_role->permissions()->attach($dev_permission);

		// $manager_role = new VRole();
        // $manager_role->id = 2;
		// $manager_role->slug = 'manager';
		// $manager_role->name = 'Manager';
		// $manager_role->save();
		// $manager_role->permissions()->attach($manager_permission);

		// $dev_role = VRole::where('slug','vendor')->first();
		// $manager_role = VRole::where('slug', 'manager')->first();
		// $dev_perm = VPermission::where('slug','edit')->first();
		// $manager_perm = VPermission::where('slug','view')->first();

        // $dev_module = VModule::where('slug','permission')->first();
		// $manager_module = VModule::where('slug','reports')->first();

		// $vendor = new User();
        // $vendor->id = 1;
		// $vendor->name = 'Company';
		// $vendor->last_name = 'vendor';
		// $vendor->email = 'company@virtualemployee.com';
		// $vendor->password = bcrypt('1234@Sk');
        // $vendor->role_id = 1;
        // $vendor->status = 1;
		// $vendor->save();
		// $vendor->roles()->attach($dev_role);
		// $vendor->permissions()->attach($dev_perm);
        // $vendor->modules()->attach($dev_module);

        // $manager = new User();
        // $manager->id = 2;
		// $manager->name = 'Manager';
		// $manager->last_name = 'vendor';
		// $manager->email = 'manager@virtualemployee.com';
		// $manager->password = bcrypt('1234@Sk');
        // $manager->role_id = 1;
        // $manager->status = 1;
		// $manager->save();
		// $manager->roles()->attach($manager_role);
		// $manager->permissions()->attach($manager_perm);
        // $manager->modules()->attach($manager_module);

    }
}
