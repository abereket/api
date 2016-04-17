<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableSurveySkills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_skills', function (Blueprint $table) {

            $table->dropForeign(['user_id']);
            $table->dropForeign(['survey_id']);
            DB::statement('ALTER TABLE `survey_skills` ADD
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
            ON DELETE CASCADE,
            ADD FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`)
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
        Schema::table('survey_skills', function (Blueprint $table) {
            //
        });
    }
}
