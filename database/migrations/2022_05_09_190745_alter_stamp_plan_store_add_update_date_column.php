<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStampPlanStoreAddUpdateDateColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('T_StampPlanStore', function (Blueprint $table) {
            $table->timestamp('updateDate')->nullable()->after('storeId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('T_StampPlanStore', function (Blueprint $table) {
            //
        });
    }
}
