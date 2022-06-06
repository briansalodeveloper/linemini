<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTDisplayTargetCuponAoAutoIncrementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T_DisplayTargetCuponAO', function (Blueprint $table) {
            $table->integer('displayTargetCuponAOId', 11)->autoIncrement()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('T_DisplayTargetCuponAO', function (Blueprint $table) {
            //
        });
    }
}
