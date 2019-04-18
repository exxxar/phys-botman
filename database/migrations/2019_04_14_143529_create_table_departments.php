<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            /** Название кафедры */
            $table->string('name');
            /** Информация о кафедре */
            $table->string('info');
            /** История */
            $table->string('history');
            /** Наука */
            $table->string('science');
            /** Абитуриенту */
            $table->string('enrollee');
            /** Специальности */
            $table->string('specialty');
            /** Учебные материалы */
            $table->string('educationalMaterials');
            /** Электронный учебник */
            $table->string('electronicTextbook');
            /** Контакты */
            $table->string('contacts');
            /** Преподаватели */
            $table->string('teachers');
            /** Занятия для школьников/школа юного физика */
            $table->string('courses');
            /** Радиофизика */
            $table->string('radioPhysics');
            /** Информационная безопасность */
            $table->string('informationSecurity');
            /** Учебные документы */
            $table->string('trainingDocuments');
            /** Олимпиада IT-профи/Региональная */
            $table->string('olympiad');
            /** Сотрудничество */
            $table->string('cooperation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('departments');
    }
}
