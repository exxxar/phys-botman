<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStaticData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staticData', function (Blueprint $table) {
            $table->increments('id');
            /** О физтехе */
            $table->string('aboutPhys');
            /** Направления подготовки */
            $table->string('directions');
            /** Научная деятельность */
            $table->string('scientificActivity');
            /** Контакты */
            $table->string('contacts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('staticData');
    }
}
