<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCuponPlanIdInMCuponPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("set session sql_mode='NO_AUTO_VALUE_ON_ZERO,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
        DB::statement("DELETE FROM `M_CuponPlan` WHERE cuponPlanId=0");
        Schema::table('M_CuponPlan', function (Blueprint $table) {
            $table->integer('cuponPlanId', 11)->autoIncrement()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('M_CuponPlan', function (Blueprint $table) {
            //
        });
    }
}
