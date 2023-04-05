<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Replies')->insert([
            [
                'message' => 'すぐに用意いたします',
                'user_id' => 1,
                'post_id' => 5,
            ],
            [
                'message' => 'それは私達の仕事ではなく、清掃業者にご連絡ください',
                'user_id' => 1,
                'post_id' => 3,
            ],
            [
                'message' => 'ここに返信1',
                'user_id' => 1,
                'post_id' => 1,
            ],
            [
                'message' => 'ここに返信2',
                'user_id' => 1,
                'post_id' => 2,
            ],
            [
                'message' => 'ここに返信3',
                'user_id' => 2,
                'post_id' => 2,
            ],
        ]);
    }
}
