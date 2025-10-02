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
    Schema::table('deliveries', function (Blueprint $table) {
        $table->enum('status', ['ready to deliver', 'in transit', 'delivered'])->default('ready to deliver');
    });
}

public function down()
{
    Schema::table('deliveries', function (Blueprint $table) {
        $table->dropColumn('status');
    });

    }
};
