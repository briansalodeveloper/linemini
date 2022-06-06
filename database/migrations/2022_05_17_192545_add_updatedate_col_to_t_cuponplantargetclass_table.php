<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatedateColToTCuponplantargetclassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T_CuponPlanTargetClass', function (Blueprint $table) {
            $table->timestamp('updateDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('T_CuponPlanTargetClass', function (Blueprint $table) {
            $table->dropColumn('updateDate');
        });
    }
}
