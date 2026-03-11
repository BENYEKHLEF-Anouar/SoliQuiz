<?php
namespace Database\Seeders;
class ReponseSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('reponses', 'reponses.csv');
    }
}
