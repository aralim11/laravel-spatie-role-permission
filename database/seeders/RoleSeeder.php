<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Super Admin', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'Admin', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'User', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
        ]);

        $permissions = DB::table('permissions')->get();

        foreach($permissions as $permission){
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permission->id,
                'role_id' => 1,
            ]); 
        }

        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => 'App\Models\User',
            'model_id' => 1,
        ]);
    }
}
