<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->boolean('is_closed')->default(false)->after('link');
            $table->boolean('is_fulfilled')->default(false)->after('link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
}
