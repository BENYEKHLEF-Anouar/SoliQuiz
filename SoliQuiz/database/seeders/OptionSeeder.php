<?php
namespace Database\Seeders;
class OptionSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('options', 'options.csv');
    }
}
