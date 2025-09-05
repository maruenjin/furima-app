<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder

  {
    public function run(): void
    {
          $sellerId = User::find(2)->id ?? (User::find(1)->id ?? (User::query()->value('id') ?? 1));

        $allowed = ['良好','やや傷や汚れあり','状態が悪い'];
        $normalize = fn($c) => in_array($c, $allowed, true) ? $c : '良好';

        $items = [
            [
                'name'        => '腕時計',
                'price'       => 15000,
                'brand'       => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition'   => '良好',
                'image_path'  => 'images/armani-mens-clock.jpg',
            ],
            [
                'name'        => 'HDD',
                'price'       => 5000,
                'brand'       => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
               
                'condition'   => '良好',
                'image_path'  => 'images/hdd-hard-disk.jpg',
            ],
            [
                'name'        => '玉ねぎ3束',
                'price'       => 300,
                'brand'       => null, 
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition'   => 'やや傷や汚れあり',
                'image_path'  => 'images/iloveimg-d.jpg',
            ],
            [
                'name'        => '革靴',
                'price'       => 4000,
                'brand'       => null,
                'description' => 'クラシックなデザインの革靴',
                'condition'   => '状態が悪い',
                'image_path'  => 'images/leather-shoes-product-photo.jpg',
            ],
            [
                'name'        => 'ノートPC',
                'price'       => 45000,
                'brand'       => null,
                'description' => '高性能なノートパソコン',
                'condition'   => '良好',
                'image_path'  => 'images/living-room-laptop.jpg',
            ],
            [
                'name'        => 'マイク',
                'price'       => 3000,
                'brand'       => null,
                'description' => '高音質のレコーディング用マイク',
                'condition'   => '良好', 
                'image_path'  => 'images/music-mic-4632231.jpg',
            ],
            [
                'name'        => 'ショルダーバッグ',
                'price'       => 3200,
                'brand'       => null,
                'description' => 'おしゃれなショルダーバッグ',
                'condition'   => 'やや傷や汚れあり',
                'image_path'  => 'images/purse-fashion-pocket.jpg',
            ],
            [
                'name'        => 'タンブラー',
                'price'       => 700,
                'brand'       => null,
                'description' => '使いやすいタンブラー',
                'condition'   => '状態が悪い',
                'image_path'  => 'images/tumbler-souvenir.jpg',
            ],
            [
                'name'        => 'コーヒーミル',
                'price'       => 2300,
                'brand'       => 'Starbucks',
                'description' => '手挽きのコーヒーミル',
                'condition'   => '良好',
                'image_path'  => 'images/waitress-with-coffee-grinder.jpg',
            ],
            [
                'name'        => 'メイクセット',
                'price'       => 2500,
                'brand'       => null,
                'description' => '便利なメイクアップセット',
                'condition'   => '良好', 
                'image_path'  => 'images/makeup-set.jpg',
            ],
        ];

        foreach ($items as $i) {
            $data = $i;
            $data['brand'] = ($data['brand'] === 'なし') ? null : $data['brand'];
            $data['condition'] = $normalize($data['condition']);
            $data['user_id'] = $sellerId;

            
            Product::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}