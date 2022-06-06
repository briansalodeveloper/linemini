<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCuponplanproductidColOfTCuponplanproductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("set session sql_mode='NO_AUTO_VALUE_ON_ZERO,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        Schema::table('T_CuponPlanProduct', function (Blueprint $table) {
            $table->integer('cuponPlanProductId', 11)->autoIncrement()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('T_CuponPlanProduct', function (Blueprint $table) {
            $table->integer('cuponPlanProductId', 11)->change();
        });
    }
}
