<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nama'     => 'Mulyo Sugiono',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('Admin123'),
            'no_telp'  => '081336064085',
        ]);
    }
}
