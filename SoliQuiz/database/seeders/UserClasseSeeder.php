<?php
namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
class UserClasseSeeder extends CsvSeeder {
    public function run() {
        foreach ($this->parseCSV('users_classes.csv') as $uc) {
            DB::table('users')->where('id', $uc['user_id'])->update(['classe_id' => $uc['classe_id']]);
        }
    }
}
