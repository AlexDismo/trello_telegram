<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'password', 'remember_token', 'email_verified_at']);
            $table->bigInteger('telegram_id')->unique()->after('id');
            $table->string('firstName')->after('telegram_id');
            $table->string('lastName')->nullable()->after('firstName');
            $table->string('username')->after('lastName');
            $table->boolean('isPremium')->default(false)->after('username');
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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->dropColumn(['telegram_id', 'firstName', 'lastName', 'username', 'isPremium']);
        });
    }
}
