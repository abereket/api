<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableSurveys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {

            $table->dropForeign(['job_id']);
            $table->dropForeign(['user_id']);
            DB::statement('ALTER TABLE `surveys` ADD
            FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`)
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
        Schema::table('surveys', function (Blueprint $table) {
            //
        });
    }
}
