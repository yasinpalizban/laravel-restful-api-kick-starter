<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('auth_groups')->insertBatch([[
            'name' => 'admin',
            'description' => 'admin'
        ], [
            'name' => 'coworker',
            'description' => 'coworker'
        ], [
            'name' => 'blogger',
            'description' => 'blogger'
        ], [
            'name' => 'member',
            'description' => 'member'
        ]]);

        DB::table('auth_groups_users')
            ->insert([
                'group_id' => '1',
                'user_id' => '1'
            ]);

    }
}
