<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfilesTableSeeder extends Seeder
{
    public function run()
    {
        Profile::create([
            'user_id' => 1,
            'image' => $this->storeImage('profiles', 'tanaka_profile_image.jpeg'),
            'postal_code' => '100-0005',
            'address' => '東京都千代田区丸の内一丁目',
            'building' => '丸の内オアゾ 15F',
        ]);
    }

    private function storeImage($dir, $fileName)
    {
        $source = resource_path("images/{$dir}/{$fileName}");

        Storage::disk('public')->put(
            "images/{$dir}/{$fileName}",
            File::get($source)
        );

        return "images/{$dir}/{$fileName}";
    }
}