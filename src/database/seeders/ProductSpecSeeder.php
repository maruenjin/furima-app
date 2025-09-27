<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSpecSeeder extends Seeder
{
    public function run(): void
    {
        
        $sellers   = User::factory()->count(3)->verified()->create();
        $sellerIds = $sellers->pluck('id')->all();

       
        $rows = [
             ['腕時計',     15000, 'Rolax',     'スタイリッシュなデザインのメンズ腕時計', 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',  '良好'],
            ['HDD',         5000, '西芝',      '大容量の外付けハードディスク',           'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',      '目立った傷や汚れなし'],
            ['玉ねぎ3束',     300, 'なし',      '新鮮な玉ねぎ3束のセット',                 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',          'やや傷や汚れあり'],
            ['革靴',        4000, null,        'クラシックなデザインの革靴',               'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg', '状態が悪い'],
            ['ノートPC',    45000, null,        '高性能なノートパソコン',                   'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',  '良好'],
            ['マイク',       8000, 'なし',      '高音質のレコーディング用マイク',           'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',   '目立った傷や汚れなし'],
            ['ショルダーバッグ', 3500, null,      'おしゃれなショルダーバッグ',               'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg','やや傷や汚れあり'],
            ['タンブラー',     500, 'なし',      '使いやすいタンブラー',                     'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',     '状態が悪い'],
            ['コーヒーミル',  4000, 'Starbacks','手動のコーヒーミル',                       'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg','良好'],
            ['メイクセット',  2500, null,        '便利なメイクアップセット',                 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg','目立った傷や汚れなし'],
         
  
        ];

        foreach ($rows as [$name, $price, $brand, $desc, $url, $cond]) {
            
           $condMap   = [
                '良好'       => '目立った傷や汚れなし',
                '状態が悪い' => '全体的に状態が悪い',
           ];
            $condition = $condMap[$cond] ?? $cond;
            $price = (int) str_replace([',', ' '], '', (string) $price);
            $brand = in_array($brand, ['なし','—','ー','']) ? null : $brand;

            Product::create([
                'user_id'     => Arr::random($sellerIds),
                'name'        => $name,
                'brand'       => in_array($brand, ['なし','ー','—']) ? null : $brand,
                'price'       => $price,
                'description' => $desc,
                'condition'   => $condition,
                'image_path'  => $url,
                'categories'  => [], 
            ]);
        }
    }
}

