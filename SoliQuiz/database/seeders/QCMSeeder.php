<?php
namespace Database\Seeders;
class QCMSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('qcms', 'qcms.csv');
    }
}
