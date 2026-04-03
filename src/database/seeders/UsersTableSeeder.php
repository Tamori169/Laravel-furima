<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '田中太郎',
            'email' => 'tanaka@example.com',
            'password' => Hash::make('tanakatanaka'),
            'email_verified_at' => null,
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => '佐藤次郎',
            'email' => 'sato@example.com',
            'password' => Hash::make('satosato'),
            'email_verified_at' => null,
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => '鈴木三郎',
            'email' => 'suzuki@example.com',
            'password' => Hash::make('suzukisuzuki'),
            'email_verified_at' => null,
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => '山田花子',
            'email' => 'yamada@example.com',
            'password' => Hash::make('yamadayamada'),
            'email_verified_at' => null,
        ];
        DB::table('users')->insert($param);
    }
}
