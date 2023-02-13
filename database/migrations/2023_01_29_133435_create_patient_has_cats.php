<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientHasCats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_has_cats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_patient_id');
            $table->foreign('study_patient_id')->on('study_patient')->references('id')->cascadeOnDelete()->cascadeOnUpdate() ;

            $table->foreignId('patient_cat_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_has_cats');
    }
}
