<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::where('email', 'admin@redpulse.com')->update(['password' => \Illuminate\Support\Facades\Hash::make('admin123')]);
        \App\Models\User::where('email', 'petugas1@redpulse.com')->update(['password' => \Illuminate\Support\Facades\Hash::make('petugas123')]);
        \App\Models\User::where('email', 'petugas2@redpulse.com')->update(['password' => \Illuminate\Support\Facades\Hash::make('petugas123')]);
        \App\Models\User::where('email', 'rs@redpulse.com')->update(['password' => \Illuminate\Support\Facades\Hash::make('rumahsakit123')]);
    }
}
