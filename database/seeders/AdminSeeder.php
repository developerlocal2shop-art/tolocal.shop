<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //DB::table('admin')->delete();
        //DB::table('roles')->delete();
        
        // $dev_permission = Permission::where('slug','add')->first();
		// $manager_permission = Permission::where('slug', 'edit')->first();
	   
		// $dev_role = new Role();
        // $dev_role->id = 1;
		// $dev_role->slug = 'administrator';
		// $dev_role->name = 'Administrator';
		// $dev_role->save();
		// $dev_role->permissions()->attach($dev_permission);

		// $manager_role = new Role();
        // $manager_role->id = 2;
		// $manager_role->slug = 'manager';
		// $manager_role->name = 'Manager';
		// $manager_role->save();
		// $manager_role->permissions()->attach($manager_permission);

		// $dev_role = Role::where('slug','administrator')->first();
		// $manager_role = Role::where('slug', 'manager')->first();
		// $dev_perm = Permission::where('slug','edit')->first();
		// $manager_perm = Permission::where('slug','view')->first();

        // $dev_module = Module::where('slug','permission_managment')->first();
		// $manager_module = Module::where('slug','dashboard')->first();

		// $administrator = new Admin();
        // $administrator->id = 1;
		// $administrator->name = 'Harish sharma';
		// $administrator->email = 'harishksharma@virtualemployee.com';
		// $administrator->password = bcrypt('peter@123');
        // $administrator->role_id = 1;
        // $administrator->status = 1;
		// $administrator->save();
		// $administrator->roles()->attach($dev_role);
		// $administrator->permissions()->attach($dev_perm);
        // $administrator->modules()->attach($dev_module);

        // $manager = new Admin();
        // $manager->id = 2;
		// $manager->name = 'Manager';
		// $manager->email = 'manager@virtualemployee.com';
		// $manager->password = bcrypt('123456');
        // $manager->role_id = 1;
        // $manager->status = 1;
		// $manager->save();
		// $manager->roles()->attach($manager_role);
		// $manager->permissions()->attach($manager_perm);
        // $manager->modules()->attach($manager_module);

    }
}
