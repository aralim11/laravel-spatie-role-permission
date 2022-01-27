<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['name' => 'category.view', 'group_id' => '1', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'category.add', 'group_id' => '1', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'category.edit', 'group_id' => '1', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'category.delete', 'group_id' => '1', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],

            ['name' => 'blog.view', 'group_id' => '2', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'blog.add', 'group_id' => '2', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'blog.edit', 'group_id' => '2', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'blog.delete', 'group_id' => '2', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],

            ['name' => 'permission.group.view', 'group_id' => '3', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'permission.group.add', 'group_id' => '3', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'permission.group.edit', 'group_id' => '3', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'permission.group.delete', 'group_id' => '3', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],

            ['name' => 'permission.view', 'group_id' => '4', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'permission.add', 'group_id' => '4', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'permission.edit', 'group_id' => '4', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'permission.delete', 'group_id' => '4', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],

            ['name' => 'role.view', 'group_id' => '5', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'role.add', 'group_id' => '5', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'role.edit', 'group_id' => '5', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'role.delete', 'group_id' => '5', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],

            ['name' => 'user.view', 'group_id' => '6', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'user.add', 'group_id' => '6', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'user.edit', 'group_id' => '6', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
            ['name' => 'user.delete', 'group_id' => '6', 'guard_name' => "web", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
        ]);
    }
}
