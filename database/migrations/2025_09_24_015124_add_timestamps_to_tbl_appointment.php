<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tbl_appointment', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_appointment', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('tbl_appointment', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();  // Auto-updates on changes
            }
        });
    }

    public function down()
    {
        Schema::table('tbl_appointment', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};