<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(
            [[
                'name' => 'super_admin',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
                [
                    'name' => 'admin',
                    'guard_name' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'content_manager',
                    'guard_name' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ], ]
        );
    }
}
