<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {

            $table->dropForeign(['agency_id']);
            DB::statement('ALTER TABLE `teams` ADD
            FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`)
            ON DELETE CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
