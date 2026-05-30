<?php
namespace Database\Seeders;
class QuestionSeeder extends CsvSeeder {
    public function run() {
        $this->seedFromCSV('questions', 'questions.csv');
    }
}
