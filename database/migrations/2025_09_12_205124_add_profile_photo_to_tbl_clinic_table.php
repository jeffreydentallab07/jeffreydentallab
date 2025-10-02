<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_technician', function (Blueprint $table) {
           
            $table->string('photo')->nullable()->after('contact_number');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_technician', function (Blueprint $table) {
        
            $table->dropColumn('photo');
        });
    }
};