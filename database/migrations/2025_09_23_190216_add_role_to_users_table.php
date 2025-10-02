<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Either enum (MySQL) or string (more portable). I usually use string:
            $table->enum('role', ['admin','staff','technician','rider'])->after('password');

            // OR with enum:
            // $table->enum('role', ['admin','staff','technician','rider'])->default('staff');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
}

   

