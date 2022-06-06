<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToMAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('M_Admin', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->after('password');
            $table->integer('role')->default(0)->after('email');
            $table->dateTime('createdDate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updateDate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('updateUser', 11)->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->tinyInteger('delFlg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('M_Admin', function (Blueprint $table) {
            $table->dropColumn('thumbnail');
            $table->dropColumn('role');
            $table->dropColumn('createdDate');
            $table->dropColumn('updateDate');
            $table->dropColumn('updateUser');
            $table->dropColumn('delFlg');
        });
    }
}
