<?php
namespace Database\Seeders;
class UserSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('users', 'users.csv');
    }
}
