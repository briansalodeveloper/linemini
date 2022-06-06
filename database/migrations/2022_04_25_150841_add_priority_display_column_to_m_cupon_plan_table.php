<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriorityDisplayColumnToMCuponPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M_CuponPlan', function (Blueprint $table) {
            $table->tinyInteger('priorityDisplayFlg')->default(0)->after('cuponType');
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
            $table->dropColumn('priorityDisplayFlg');
        });
    }
}
