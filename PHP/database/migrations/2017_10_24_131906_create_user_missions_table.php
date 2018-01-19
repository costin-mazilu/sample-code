<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_missions', function (Blueprint $table) {
            $table->integer('user_id', false, true);
            $table->integer('mission_id', false, true);
            $table->primary(['user_id', 'mission_id']);
            $table->boolean('main')->default(0);
            $table->integer('level', false, true)->default(1);
            $table->tinyInteger('progress', false, true)->default(0);
            $table->integer('xp', false, true)->default(0);
            $table->integer('startXp', false, true)->default(0);
            $table->integer('endXp', false, true)->default(500);
            $table->boolean('completed_hide')->default(0);
            $table->boolean('completed_last')->default(0);
            $table->text('tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_missions');
    }
}
