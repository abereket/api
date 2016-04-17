<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableJobSkills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_skills', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            DB::statement('ALTER TABLE `job_skills` ADD
            FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`)
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
        Schema::table('job_skills', function (Blueprint $table) {
            //
        });
    }
}
