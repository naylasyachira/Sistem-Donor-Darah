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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code', 20)->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade');
            $table->date('request_date');
            $table->string('blood_type', 3);
            $table->enum('rhesus', ['+', '-']);
            $table->integer('quantity_bag');
            $table->enum('urgency', ['Normal', 'Penting', 'Darurat'])->default('Normal');
            $table->text('purpose')->nullable();
            $table->enum('status', ['Menunggu Persetujuan', 'Disetujui', 'Ditolak', 'Selesai'])->default('Menunggu Persetujuan');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
