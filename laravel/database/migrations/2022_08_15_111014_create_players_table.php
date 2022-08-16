<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('profession');
            $table->string('blood_alliance');
            $table->timestamps();
        });

        DB::table('admin_menu')->insert([
            'parent_id' => 0,
            'order' => 0,
            'title' => 'Player',
            'icon' => '',
            'uri' => 'players',
            'show' => 1,
            'extension' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');

        DB::table('admin_menu')->where('title', 'Player')->delete();
    }
}
