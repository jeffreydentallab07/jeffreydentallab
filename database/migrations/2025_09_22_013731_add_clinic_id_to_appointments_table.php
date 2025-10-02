<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('tbl_appointment', function (Blueprint $table) {
        $table->unsignedBigInteger('clinic_id')->after('id'); // adjust position
    });
}

public function down()
{
    Schema::table('tbl_appointment', function (Blueprint $table) {
        $table->dropColumn('clinic_id');
    });

    }
};
