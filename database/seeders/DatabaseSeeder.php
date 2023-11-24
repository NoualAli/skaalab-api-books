<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'NLDev',
            'email' => 'noualdev@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::factory(5)->create();
        Author::factory(5)->create();
    }
}
