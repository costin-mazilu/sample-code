<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mission_id')->unsigned();
            $table->foreign('mission_id')->references('id')->on('missions')->onDelete('cascade');
            $table->integer('task_group_id')->unsigned()->nullable();
            $table->foreign('task_group_id')->references('id')->on('task_groups')->onDelete('set null');

            $table->string('name');
            $table->tinyInteger('type');
            $table->tinyInteger('importance');
            $table->tinyInteger('difficulty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
