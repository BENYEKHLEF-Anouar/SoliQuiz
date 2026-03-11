<?php
namespace Database\Seeders;
class CompetenceSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('competences', 'competences.csv');
    }
}
