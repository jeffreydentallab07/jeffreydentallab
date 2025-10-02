<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('patients', function (Blueprint $table) {
        $table->id(); // Auto-increment primary key
        $table->string('name');
        $table->string('gender')->nullable();
        $table->date('birthdate')->nullable();
        $table->string('contact_number', 20)->nullable();
        $table->string('address')->nullable();
        $table->timestamps(); // created_at and updated_at
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
