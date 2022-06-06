<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Ryne\LaravelStarter\DBHelper;

class DropUniqueKeyInEmailInAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DBHelper::keyDelete('M_Admin', 'm_admin_email_unique', 'index');
        DBHelper::keyDelete('M_Admin', 'email', 'index');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('M_Admin', function (Blueprint $table) {
            $table->unique('email', 'm_admin_email_unique');
        });
    }
}
