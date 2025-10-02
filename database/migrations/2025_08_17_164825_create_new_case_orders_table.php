<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_orders', function (Blueprint $table) {
            $table->id('co_id');
            $table->unsignedBigInteger('patient_id'); 
            $table->string('dentist_name'); 
            $table->string('clinic_name'); 
            $table->enum('case_type', ['Denture Repair', 'Crown & Bridge', 'Orthodontic', 'Partial Denture', 'Others'])->nullable();
            $table->text('notes')->nullable();
            $table->string('recieve_by')->nullable();
            $table->timestamp('recieve_at')->nullable();
            $table->timestamps();
        
            $table->foreign('patient_id')
                  ->references('patient_id')
                  ->on('patients')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_orders');
    }
};
