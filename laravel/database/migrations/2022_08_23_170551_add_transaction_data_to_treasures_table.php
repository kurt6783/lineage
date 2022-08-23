<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionDataToTreasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treasures', function (Blueprint $table) {
            $table->integer('accountant_id')->nullable();
            $table->dropColumn('unit_price');
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
            $table->dropColumn('accountant_id');
            $table->integer('unit_price')->nullable();
        });
    }
}
