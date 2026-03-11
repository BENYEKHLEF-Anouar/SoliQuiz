<?php
namespace Database\Seeders;
class ChoixReponseSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('choix_reponses', 'choix_reponses.csv');
    }
}
