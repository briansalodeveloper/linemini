<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddDraftFlgColumnToMMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M_Message', function (Blueprint $table) {
            $table->tinyInteger('draftFlg')->default(0)->after('storeId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('M_Message', function (Blueprint $table) {
            $table->dropColumn('draftFlg');
        });
    }
}
