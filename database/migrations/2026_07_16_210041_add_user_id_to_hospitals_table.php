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
        Schema::table('hospitals', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id');
        });

        // Link existing users based on email
        $hospitals = \Illuminate\Support\Facades\DB::table('hospitals')->get();
        foreach ($hospitals as $hospital) {
            $user = \Illuminate\Support\Facades\DB::table('users')->where('email', $hospital->email)->first();
            if ($user) {
                \Illuminate\Support\Facades\DB::table('hospitals')
                    ->where('id', $hospital->id)
                    ->update(['user_id' => $user->id]);
            }
        }

        Schema::table('hospitals', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
