<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade'); // ここに 'cascade' を追加

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // ここに 'cascade' を追加
        });
    }

    public function down()
    {
        Schema::dropIfExists('replies');
    }
};
