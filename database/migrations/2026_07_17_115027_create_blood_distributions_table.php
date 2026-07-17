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
        Schema::create('blood_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('distribution_code', 50)->unique();
            $table->foreignId('blood_request_id')->constrained('blood_requests')->onDelete('cascade');
            $table->enum('status', ['Diproses', 'Dikirim', 'Diterima'])->default('Diproses');
            $table->date('distribution_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_distributions');
    }
};
