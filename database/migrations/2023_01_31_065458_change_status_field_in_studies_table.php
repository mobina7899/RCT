<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusFieldInStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('studies', function (Blueprint $table) {
            $table->dropColumn('status') ;
           $table->tinyInteger('status')->default(0) ;
           $table->tinyInteger('step_number');
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
            $table->dropColumn('status');
           $table->boolean('status')->default(false);
           $table->dropColumn('step_number');

        });
    }
}
