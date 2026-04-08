<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'name' => '田中太郎',
            'image' => '/images/profiles/Tanaka__profile-image.jpg',
            'postal_code' => '1000005',
            'address' => '東京都千代田区丸の内一丁目',
            'building' => '丸の内オアゾ 15F',
        ];
        DB::table('profiles')->insert($param);
        $param = [
            'user_id' => 2,
            'name' => '佐藤次郎',
            'image' => '/images/profiles/Sato__profile-image.jpg',
            'postal_code' => '5300011',
            'address' => '大阪府大阪市北区大深町',
            'building' => 'グランフロント大阪 北館 7F',
        ];
        DB::table('profiles')->insert($param);
        $param = [
            'user_id' => 3,
            'name' => '鈴木三郎',
            'image' => '/images/profiles/Suzuki__profile-image.jpg',
            'postal_code' => '8120012',
            'address' => '福岡県福岡市博多区博多駅中央街',
            'building' => null,
        ];
        DB::table('profiles')->insert($param);
        $param = [
            'user_id' => 4,
            'name' => '山田花子',
            'image' => '/images/profiles/Yamada__profile-image.jpg',
            'postal_code' => '0600806',
            'address' => '北海道札幌市北区北６条西４丁目',
            'building' => 'パセオ EAST 1F',
        ];
        DB::table('profiles')->insert($param);
    }
}
