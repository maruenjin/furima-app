<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('zip_code')->nullable()->after('email');
            $table->string('address')->nullable()->after('zip_code');
            $table->string('building')->nullable()->after('address');
            $table->string('avatar_path')->nullable()->after('building');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['zip_code','address','building','avatar_path']);
        });
    }
}
