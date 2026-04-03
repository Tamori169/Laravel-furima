<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
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
            'name' => '腕時計',
            'image' => '/images/samples/item1_watch.jpg',
            'price' => 15000,
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => 1,
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => 'HDD',
            'image' => '/images/samples/item2_hdd.jpg',
            'price' => 5000,
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'condition_id' => 2,
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'name' => '玉ねぎ3束',
            'image' => '/images/samples/item3_onions.jpg',
            'price' => 300,
            'brand' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'condition_id' => 3,
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 2,
            'name' => '革靴',
            'image' => '/images/samples/item4_shoes.jpg',
            'price' => 4000,
            'brand' => null,
            'description' => 'クラシックなデザインの革靴',
            'condition_id' => 4,
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 2,
            'name' => 'ノートPC',
            'image' => '/images/samples/item5_laptop.jpg',
            'price' => 45000,
            'brand' => null,
            'description' => '高性能なノートパソコン',
            'condition_id' => 1,
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 2,
            'name' => 'マイク',
            'image' => '/images/samples/item6_microphone.jpg',
            'price' => 8000,
            'brand' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'condition_id' => 2,
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 3,
            'name' => 'ショルダーバッグ',
            'image' => '/images/samples/item7_bag.jpg',
            'price' => 3500,
            'brand' => null,
            'description' => 'おしゃれなショルダーバッグ',
            'condition_id' => 3,
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 3,
            'name' => 'タンブラー',
            'image' => '/images/samples/item8_tumbler.jpg',
            'price' => 500,
            'brand' => 'なし',
            'description' => '使いやすいタンブラー',
            'condition_id' => 4,
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 3,
            'name' => 'コーヒーミル',
            'image' => '/images/samples/item9_mill.jpg',
            'price' => 4000,
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'condition_id' => 1,
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 3,
            'name' => 'メイクセット',
            'image' => '/images/samples/item10_cosmetics.jpg',
            'price' => 2500,
            'brand' => null,
            'description' => '便利なメイクアップセット',
            'condition_id' => 2,
        ];
        DB::table('items')->insert($param);
    }
}
