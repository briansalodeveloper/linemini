<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFlyerDisplayStoreIdColumnInTDisplayStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('DELETE FROM T_FlyerDisplayStore WHERE flyerDisplayStoreId=0 AND flyerPlanId IS NULL;');
        Schema::table('T_FlyerDisplayStore', function (Blueprint $table) {
            $table->increments('flyerDisplayStoreId')->comments('ID')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('T_FlyerDisplayStore', function (Blueprint $table) {
            $table->integer('flyerDisplayStoreId')->comments('ID')->change();
        });
    }
}
