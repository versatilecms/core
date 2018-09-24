<?php

use Illuminate\Database\Migrations\Migration;

class AddVersatileUserFields extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('id')->default('users/default.png');
            }
            $table->unsignedInteger('role_id')->nullable()->after('id');
            $table->text('settings')->nullable()->after('remember_token');

            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'avatar')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('avatar');
            });
        }
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('role_id');
            });
        }
    }
}
