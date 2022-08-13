<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Config\ModuleAuthConfig;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authConfig = new ModuleAuthConfig();
        DB::table('users')->insert([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '0918000',
            "active" => "1",
            //default password is == pass
            "password_hash" => '$2y$10$7i2pxCY7hvp7BQfpkVAXgulJkC/f8i1g71YQ/TRBuJvhPsKLAsAt6',
            "image" => $authConfig->defualtUserProfile,
            "first_name"=>"admin",
            "last_name"=>"admin",
        ]);
    }
}
