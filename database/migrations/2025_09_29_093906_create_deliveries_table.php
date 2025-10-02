<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_delivery', function (Blueprint $table) { // Use 'tbl_delivery' here
            $table->increments('delivery_id'); // Assuming primary key is 'delivery_id' from your context
            $table->unsignedInteger('appointment_id')->nullable(); // Changed to unsignedInteger
            $table->foreign('appointment_id')->references('appointment_id')->on('tbl_appointment')->onDelete('set null');

            $table->unsignedBigInteger('rider_id')->nullable();
            $table->foreign('rider_id')->references('id')->on('users')->onDelete('set null');

            $table->enum('delivery_status', ['ready to deliver', 'in transit', 'delivered', 'cancelled'])->default('ready to deliver'); // Added 'cancelled' for completeness
            $table->timestamp('delivery_date')->nullable(); // Added delivery_date from your controller
            $table->timestamps(); // For created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_delivery');
    }
};