<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSendTargetMessageStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T_SendTargetMessageStore', function (Blueprint $table) {
            $table->increments('sendTargetMessageStoreId');
            $table->integer('messageId');
            $table->string('storeId', 4);
            $table->timestamp('updateDate');
            $table->string('updateUser', 11)->charset('utf8mb4')->collation('utf8mb4_general_ci')->nullable();
            $table->tinyInteger('delFlg')->default(0);
        });

        Schema::table('M_Message', function (Blueprint $table) {
            $table->dropColumn('storeId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T_SendTargetMessageStore');

        Schema::table('M_Message', function (Blueprint $table) {
            $table->tinyInteger('storeId')->default(0);
        });
    }
}
