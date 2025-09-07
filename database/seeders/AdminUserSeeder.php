<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder {
    public function run(): void {
        Admin::updateOrCreate(
            ['email' => 'ecplus1@iplusone.co.jp'],
            ['name' => 'Site Admin', 'password' => Hash::make('password')]
        );
    }
}
