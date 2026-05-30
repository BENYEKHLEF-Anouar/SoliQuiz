<?php
namespace Database\Seeders;
class UniteApprentissageSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('unites_apprentissage', 'unites_apprentissage.csv');
    }
}
