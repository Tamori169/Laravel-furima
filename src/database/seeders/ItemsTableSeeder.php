<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        Item::create([
            'user_id' => 1,
            'name' => '腕時計',
            'image' => $this->storeImage('items', 'item1_watch.jpeg'),
            'price' => 15000,
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => 1,
        ]);

        Item::create([
            'user_id' => 1,
            'name' => 'HDD',
            'image' => $this->storeImage('items', 'item2_hdd.jpeg'),
            'price' => 5000,
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'condition_id' => 2,
        ]);

        Item::create([
            'user_id' => 1,
            'name' => '玉ねぎ3束',
            'image' => $this->storeImage('items', 'item3_onions.jpeg'),
            'price' => 300,
            'brand' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'condition_id' => 3,
        ]);

        Item::create([
            'user_id' => 2,
            'name' => '革靴',
            'image' => $this->storeImage('items', 'item4_shoes.jpeg'),
            'price' => 4000,
            'brand' => null,
            'description' => 'クラシックなデザインの革靴',
            'condition_id' => 4,
        ]);

        Item::create([
            'user_id' => 2,
            'name' => 'ノートPC',
            'image' => $this->storeImage('items', 'item5_laptop.jpeg'),
            'price' => 45000,
            'brand' => null,
            'description' => '高性能なノートパソコン',
            'condition_id' => 1,
        ]);

        Item::create([
            'user_id' => 2,
            'name' => 'マイク',
            'image' => $this->storeImage('items', 'item6_microphone.jpeg'),
            'price' => 8000,
            'brand' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'condition_id' => 2,
        ]);

        Item::create([
            'user_id' => 3,
            'name' => 'ショルダーバッグ',
            'image' => $this->storeImage('items', 'item7_bag.jpeg'),
            'price' => 3500,
            'brand' => null,
            'description' => 'おしゃれなショルダーバッグ',
            'condition_id' => 3,
        ]);

        Item::create([
            'user_id' => 3,
            'name' => 'タンブラー',
            'image' => $this->storeImage('items', 'item8_tumbler.jpeg'),
            'price' => 500,
            'brand' => 'なし',
            'description' => '使いやすいタンブラー',
            'condition_id' => 4,
        ]);

        Item::create([
            'user_id' => 3,
            'name' => 'コーヒーミル',
            'image' => $this->storeImage('items', 'item9_mill.jpeg'),
            'price' => 4000,
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'condition_id' => 1,
        ]);

        Item::create([
            'user_id' => 3,
            'name' => 'メイクセット',
            'image' => $this->storeImage('items', 'item10_cosmetics.jpeg'),
            'price' => 2500,
            'brand' => null,
            'description' => '便利なメイクアップセット',
            'condition_id' => 2,
        ]);
    }

    private function storeImage($dir, $fileName)
    {
        $source = resource_path("images/{$dir}/{$fileName}");

        Storage::disk('public')->put(
            "images/{$dir}/{$fileName}",
            File::get($source)
        );

        return "/images/{$dir}/{$fileName}";
    }
}