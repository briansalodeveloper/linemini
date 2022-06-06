<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTDisplayTargetMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('T_SendTargetMessage', function (Blueprint $table) {
            $table->increments('sendTargetMessageId');
            $table->integer('messageId');
            $table->string('kumicd', 8);
            $table->timestamp('updateDate');
            $table->string('updateUser', 11)->charset('utf8mb4')->collation('utf8mb4_general_ci')->nullable();
            $table->tinyInteger('delFlg')->default(0);
        });

        Schema::create('T_SendTargetMessageAO', function (Blueprint $table) {
            $table->increments('sendTargetMessageAOId');
            $table->integer('messageId');
            $table->integer('affiliationOfficeId');
            $table->timestamp('updateDate');
            $table->string('updateUser', 11)->charset('utf8mb4')->collation('utf8mb4_general_ci')->nullable();
            $table->tinyInteger('delFlg')->default(0);
        });

        Schema::create('T_SendTargetMessageUB', function (Blueprint $table) {
            $table->increments('sendTargetMessageUBId');
            $table->integer('messageId');
            $table->integer('utilizationBusinessId');
            $table->timestamp('updateDate');
            $table->string('updateUser', 11)->charset('utf8mb4')->collation('utf8mb4_general_ci')->nullable();
            $table->tinyInteger('delFlg')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('T_SendTargetMessage');
        Schema::dropIfExists('T_SendTargetMessageAO');
        Schema::dropIfExists('T_SendTargetMessageUB');
    }
}
