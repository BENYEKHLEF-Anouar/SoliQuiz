<?php
namespace Database\Seeders;
class ClasseSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('classes', 'classes.csv');
    }
}
