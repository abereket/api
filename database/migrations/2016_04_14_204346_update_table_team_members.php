<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableTeamMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_members', function (Blueprint $table) {

            $table->dropForeign(['team_id']);
            $table->dropForeign(['user_id']);
            DB::statement('ALTER TABLE `team_members` ADD
            FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`)
            ON DELETE CASCADE,
            ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
            ON DELETE CASCADE ');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_members', function (Blueprint $table) {
            //
        });
    }
}
