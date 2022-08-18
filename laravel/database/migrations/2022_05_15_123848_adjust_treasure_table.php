<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustTreasureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treasures', function (Blueprint $table) {
            $table->string('title')->after('user_id')->default('');
            $table->integer('selling_price')->nullable()->default(null)->change();
            $table->integer('unit_price')->nullable()->default(null)->change();
            $table->dropColumn('status')->default(0)->change();
        });
        Schema::table('treasures', function (Blueprint $table) {
            $table->integer('status')->after('unit_price')->default(0);
        });

        Schema::table('treasure_distributions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('treasure_distributions', function (Blueprint $table) {
            $table->string('player_name')->after('user_id');
            $table->integer('status')->after('product')->default(0);
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
            $table->dropColumn('title');
            $table->string('selling_price')->change();
            $table->string('unit_price')->change();
            $table->string('status')->change();
        });

        Schema::table('treasure_distributions', function (Blueprint $table) {
            $table->dropColumn('player_name');
            $table->string('status')->change();
        });
    }
}
