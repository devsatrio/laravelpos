<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'guard_name'=>'web',
            ],
            [
                'name' => 'super admin',
                'guard_name'=>'web',
            ]

        ];
        DB::table('roles')->insert($roles);
    }
}
