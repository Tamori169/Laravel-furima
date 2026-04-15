<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfilesTableSeeder extends Seeder
{
    public function run()
    {
        $param = [
            'user_id' => 1,
            'image' => $this->storeImage('profiles', 'Tanaka__profile-image.jpg'),
            'postal_code' => '100-0005',
            'address' => '東京都千代田区丸の内一丁目',
            'building' => '丸の内オアゾ 15F',
        ];
        DB::table('profiles')->insert($param);

        $param = [
            'user_id' => 2,
            'image' => $this->storeImage('profiles', 'Sato__profile-image.jpg'),
            'postal_code' => '530-0011',
            'address' => '大阪府大阪市北区大深町',
            'building' => 'グランフロント大阪 北館 7F',
        ];
        DB::table('profiles')->insert($param);

        $param = [
            'user_id' => 3,
            'image' => $this->storeImage('profiles', 'Suzuki__profile-image.jpg'),
            'postal_code' => '812-0012',
            'address' => '福岡県福岡市博多区博多駅中央街',
            'building' => null,
        ];
        DB::table('profiles')->insert($param);

        $param = [
            'user_id' => 4,
            'image' => $this->storeImage('profiles', 'Yamada__profile-image.jpg'),
            'postal_code' => '060-0806',
            'address' => '北海道札幌市北区北６条西４丁目',
            'building' => 'パセオ EAST 1F',
        ];
        DB::table('profiles')->insert($param);
    }

    private function storeImage($dir, $fileName)
    {
        $source = resource_path("images/{$dir}/{$fileName}");

        Storage::disk('public')->put(
            "images/{$dir}/{$fileName}",
            File::get($source)
        );

        return "/storage/images/{$dir}/{$fileName}";
    }
}