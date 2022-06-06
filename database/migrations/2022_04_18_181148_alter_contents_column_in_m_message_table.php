<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContentsColumnInMMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M_Message', function (Blueprint $table) {
            $table->text('contents')->nullable()->charset('utf8mb4')->collation('utf8mb4_general_ci')->change();
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
            $table->text('contents')->nullable(false)->charset('utf8mb4')->collation('utf8mb4_general_ci')->change();
        });
    }
}
