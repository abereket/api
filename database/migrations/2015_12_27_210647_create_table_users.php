<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->binary('uuid',32);
            $table->string('first_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->string('email',60);
            $table->string('password',60)->nullable();
            $table->enum('type',['recruiter','candidate','agency','zemployee']);
            $table->boolean('verified')->default(0);
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
