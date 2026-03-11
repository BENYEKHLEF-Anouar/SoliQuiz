<?php
namespace Database\Seeders;
class SeanceSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('seances', 'seances.csv');
    }
}
