<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePhysMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physMap', function (Blueprint $table) {
            $table->increments('id');
            /** Этаж */
            $table->string('floor');
            /** Кабинеты на этаже, 
             * формат : '100-999' 
             * */
            $table->string('classrooms', 7);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('physMap');
    }
}
