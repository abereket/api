<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Faker\Provider\Base;
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
            $table->string('uuid',36)->default(\Faker\Provider\Uuid::uuid());
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('email',60)->index();
            $table->string('password',60);
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
