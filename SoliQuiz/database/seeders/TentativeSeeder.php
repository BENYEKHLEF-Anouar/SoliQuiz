<?php
namespace Database\Seeders;
class TentativeSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('tentatives', 'tentatives.csv');
    }
}
