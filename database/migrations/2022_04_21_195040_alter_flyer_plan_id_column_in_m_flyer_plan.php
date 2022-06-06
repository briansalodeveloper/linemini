<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFlyerPlanIdColumnInMFlyerPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M_FlyerPlan', function (Blueprint $table) {
            $table->integer('flyerPlanId', 11)->autoIncrement()->change();
        });

        Schema::table('T_DisplayTargetFlyer', function (Blueprint $table) {
            $table->integer('displayTargetFlyerId', 11)->autoIncrement()->change();
            $table->string('kumicd', 8)->unsigned()->change();
        });

        Schema::table('T_DisplayTargetFlyerUB', function (Blueprint $table) {
            $table->integer('displayTargetFlyerUBId', 11)->autoIncrement()->change();
        });

        Schema::table('T_DisplayTargetFlyerAO', function (Blueprint $table) {
            $table->integer('displayTargetFlyerAOId', 11)->autoIncrement()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('M_FlyerPlan', function (Blueprint $table) {
            //
        });

        Schema::table('T_DisplayTargetFlyer', function (Blueprint $table) {
            //
        });

        Schema::table('T_DisplayTargetFlyerUB', function (Blueprint $table) {
            //
        });

        Schema::table('T_DisplayTargetFlyerAO', function (Blueprint $table) {
            //
        });
    }
}
