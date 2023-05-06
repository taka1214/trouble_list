<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('replies')) {
            Schema::create('replies', function (Blueprint $table) {
                $table->id();
                $table->text('message');
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('owner_id')->nullable();
                $table->timestamps();
                $table->string('image_file')->nullable();

                $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
};
