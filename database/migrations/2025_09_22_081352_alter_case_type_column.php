<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_case_order', function (Blueprint $table) {
            $table->string('case_type', 20)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_case_order', function (Blueprint $table) {
            $table->string('case_type', 5)->change(); 
        });
    }
};
