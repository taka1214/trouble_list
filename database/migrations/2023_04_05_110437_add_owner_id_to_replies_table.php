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
            if (!Schema::hasColumn('replies', 'owner_id')) {
                $table->unsignedBigInteger('owner_id')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            if (Schema::hasColumn('replies', 'owner_id')) {
                $table->dropColumn('owner_id');
            }
        });
    }
};
