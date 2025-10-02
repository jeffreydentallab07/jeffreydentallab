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
    Schema::table('tbl_billing', function ($table) {
        $table->timestamps(); // adds created_at and updated_at
    });
}

public function down()
{
    Schema::table('tbl_billing', function ($table) {
        $table->dropTimestamps();
    });
}

  
};
