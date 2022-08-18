<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RedesignTreasureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treasures', function (Blueprint $table) {
            $table->string('boss_name')->after('user_id')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->dateTime('kill_at')->nullable();
            $table->longText('description')->nullable();
            $table->renameColumn('user_id', 'owner');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treasures', function (Blueprint $table) {
            $table->dropColumn('boss_name');
            $table->dropColumn('deadline');
            $table->dropColumn('kill_at');
            $table->dropColumn('description');
            $table->renameColumn('owner', 'user_id');
        });
    }
}
