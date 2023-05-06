<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            [
                'title' => 'ここにタイトル',
                'body' => 'ここに本文',
                'created_at' => NOW()
            ],
            [
                'title' => 'This is a title',
                'body' => 'This is a body',
                'created_at' => NOW()
            ],
            [
                'title' => 'トイレが汚い',
                'body' => 'トイレの便座、特に座るところを綺麗に掃除してほしいです。',
                'created_at' => NOW()
            ],
            [
                'title' => 'お風呂の鍵が壊れています',
                'body' => 'B棟の男性用の手前側のお風呂の鍵が閉まらないです。',
                'created_at' => NOW()
            ],
            [
                'title' => 'お風呂場に棚がほしい',
                'body' => 'シャンプーやボディーソープ服などを置く棚がほしいです。',
                'created_at' => NOW()
            ],
        ]);
    }
}
