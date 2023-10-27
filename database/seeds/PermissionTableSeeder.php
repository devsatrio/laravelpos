<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            [
                'name' => 'view-users',
                'guard_name'=>'web',
            ],
            [
                'name' => 'create-users',
                'guard_name'=>'web',
            ],
            [
                'name' => 'edit-users',
                'guard_name'=>'web',
            ],
            [
                'name' => 'delete-users',
                'guard_name'=>'web',
            ],
            [
                'name' => 'view-roles',
                'guard_name'=>'web',
            ],
            [
                'name' => 'create-roles',
                'guard_name'=>'web',
            ],
            [
                'name' => 'edit-roles',
                'guard_name'=>'web',
            ],
            [
                'name' => 'delete-roles',
                'guard_name'=>'web',
            ],
            [
                'name' => 'setting-web',
                'guard_name'=>'web',
            ]

        ];
        DB::table('permissions')->insert($permission);
    }
}
