<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInStudyPatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('study_patient', function (Blueprint $table) {
          $table->integer('block');
          $table->string('group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('study_patient', function (Blueprint $table) {
            $table->dropColumn('block');
            $table->dropColumn('group');
        });
    }
}
