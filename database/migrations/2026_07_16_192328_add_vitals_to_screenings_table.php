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
        Schema::table('screenings', function (Blueprint $table) {
            $table->string('tekanan_darah')->nullable()->after('screening_date');
            $table->decimal('berat_badan', 5, 2)->nullable()->after('tekanan_darah');
            $table->decimal('tinggi_badan', 5, 2)->nullable()->after('berat_badan');
            $table->decimal('hemoglobin', 5, 2)->nullable()->after('tinggi_badan');
            $table->decimal('suhu_tubuh', 4, 1)->nullable()->after('hemoglobin');
            $table->integer('denyut_nadi')->nullable()->after('suhu_tubuh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screenings', function (Blueprint $table) {
            $table->dropColumn([
                'tekanan_darah',
                'berat_badan',
                'tinggi_badan',
                'hemoglobin',
                'suhu_tubuh',
                'denyut_nadi'
            ]);
        });
    }
};
