<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTCuponPlanStoreAutoIncrementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $cupon = DB::table('T_CuponPlanStore')->latest('cuponPlanProductId')->first(['cuponPlanProductId']);

        if (!empty($cupon)) {
            $lastestId = $cupon->cuponPlanProductId;
            DB::table('T_CuponPlanStore')->where('cuponPlanProductId', 0)->update(['cuponPlanProductId' => $lastestId + 1]);
        }

        Schema::table('T_CuponPlanStore', function (Blueprint $table) {
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
        Schema::table('T_CuponPlanStore', function (Blueprint $table) {
            //
        });
    }
}
