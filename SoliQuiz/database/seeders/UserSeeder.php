<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends CsvSeeder
{
    public function run()
    {
        foreach ($this->parseCSV('users.csv') as $userData) {
            // On s'assure que le mot de passe est haché
            $userData['password'] = Hash::make($userData['password'] ?? 'password');
            
            DB::table('users')->insert($userData);
        }
    }
}
