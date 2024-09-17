<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Hala Ibrahim',
            'email'=>'hala1@gmail.com',
            'password'=>Hash::make('password'),
            'phone_number'=>'201234567656'
        ]);

        DB::table('users')->insert([
            'name'=>'Hala Ibrahim',
            'email'=>'hala2@gmail.com',
            'password'=>Hash::make('password'),
            'phone_number'=>'201234569046'
        ]);
    }
}
