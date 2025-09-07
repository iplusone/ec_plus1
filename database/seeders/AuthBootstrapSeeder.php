<?php

namespace Database\Seeders;

use App\Models\{User, Merchant};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthBootstrapSeeder extends Seeder
{
    public function run(): void
    {
        // $merchant = Merchant::firstOrCreate(
        //     ['slug'=>'default'],
        //     ['name'=>'Default Store','is_active'=>true]
        // );

        $admin = User::firstOrCreate(
            ['email'=>'admin@example.com'],
            ['name'=>'Admin','password'=>Hash::make('password'), 'role'=>'admin']
        );

        $merchant = User::firstOrCreate(
            ['email'=>'merchant@example.com'],
            ['name'=>'merchant','password'=>Hash::make('password'), 'role'=>'merchant']
        );

        $merchant->users()->syncWithoutDetaching([$merchant->id]);
    }
}
