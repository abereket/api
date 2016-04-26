<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSurveySkillIdToSurveyResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_results', function (Blueprint $table) {
            $table->integer('survey_skill_id')->unsigned()->after('survey_id');
            $table->foreign('survey_skill_id')->references('id')->on('survey_skills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_results', function (Blueprint $table) {
            //
        });
    }
}
