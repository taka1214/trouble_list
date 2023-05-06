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
        Schema::table('replies', function (Blueprint $table) {
            if (!Schema::hasColumn('replies', 'image_file')) {
                $table->string('image_file')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            if (Schema::hasColumn('replies', 'image_file')) {
                $table->dropColumn('image_file');
            }
        });
    }
};
