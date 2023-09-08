<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => "RiMoc",
            'name' => "Surin Matharin",
            'email' => "surin.2543.matharin@gmail.com",
            'password' => Hash::make("0874669133zA!"),
        ])->assignRole('dev')->givePermissionTo('edit all');
    }
}
