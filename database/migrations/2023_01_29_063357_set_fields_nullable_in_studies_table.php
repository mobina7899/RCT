<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetFieldsNullableInStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('studies', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('studies_type')->nullable()->change();
            $table->string('study_design')->nullable()->change();
            $table->string('start_date')->nullable()->change();
            $table->string('end_date')->nullable()->change();
            $table->string('file')->nullable()->change();
            $table->string('purpose_study')->nullable()->change();
            $table->string('phase_study')->nullable()->change();
            $table->string('randomization')->nullable()->change();
            $table->string('blinding')->nullable()->change();

            $table->boolean('placebo')->nullable()->change();

            $table->date('start_date_illness')->nullable()->change();
            $table->date('end_date_illness')->nullable()->change();
            $table->boolean('termination_illness')->nullable()->change();

            $table->date('start_get_sick_ended')->nullable()->change();
            $table->date('end_get_sick_ended')->nullable()->change();
            $table->unsignedBigInteger('gender_id')->nullable()->change();
            $table->string('minimum_age')->nullable()->change();
            $table->string('maximum_age')->nullable()->change();
            $table->unsignedBigInteger('researcher_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('studies', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->string('name')->nullable(false)->change();
            $table->string('studies_type')->nullable(false)->change();
            $table->string('study_design')->nullable(false)->change();
            $table->string('start_date')->nullable(false)->change();
            $table->string('end_date')->nullable(false)->change();
            $table->string('file')->nullable(false)->change();
            $table->string('purpose_study')->nullable(false)->change();
            $table->string('phase_study')->nullable(false)->change();
            $table->string('randomization')->nullable(false)->change();
            $table->string('blinding')->nullable(false)->change();

            $table->boolean('placebo')->nullable(false)->change();

            $table->date('start_date_illness')->nullable(false)->change();
            $table->date('end_date_illness')->nullable(false)->change();
            $table->boolean('termination_illness')->nullable(false)->change();

            $table->date('start_get_sick_ended')->nullable(false)->change();
            $table->date('end_get_sick_ended')->nullable(false)->change();
            $table->unsignedBigInteger('gender_id')->nullable(false)->change();
            $table->string('minimum_age')->nullable(false)->change();
            $table->string('maximum_age')->nullable(false)->change();
            $table->unsignedBigInteger('researcher_id')->nullable(false)->change();
        });
    }
}
