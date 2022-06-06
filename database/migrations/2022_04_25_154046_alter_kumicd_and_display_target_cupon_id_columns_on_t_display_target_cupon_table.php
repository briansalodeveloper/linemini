<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterKumicdAndDisplayTargetCuponIdColumnsOnTDisplayTargetCuponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T_DisplayTargetCupon', function (Blueprint $table) {
            $table->increments('displayTargetCuponId')->change();
            $table->string('kumicd', 8)->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('T_DisplayTargetCupon', function (Blueprint $table) {
            //
        });
    }
}
