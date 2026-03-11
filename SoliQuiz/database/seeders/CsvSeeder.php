<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class CsvSeeder extends Seeder
{
    protected function parseCSV($filename)
    {
        $filepath = database_path("data/{$filename}");
        if (!file_exists($filepath)) {
            Log::warning("Fichier introuvable : {$filepath}");
            return [];
        }

        $data = [];
        if (($handle = fopen($filepath, "r")) !== false) {
            $headers = fgetcsv($handle, 1000, ",");
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                // Ignore empty lines or malformed rows
                if (count($row) !== count($headers)) {
                    continue;
                }
                $item = [];
                foreach ($headers as $i => $header) {
                    $value = $row[$i] === '' ? null : $row[$i];
                    $item[trim($header)] = $value;
                }
                $data[] = $item;
            }
            fclose($handle);
        }
        return $data;
    }

    protected function seedFromCSV($table, $filename)
    {
        $data = $this->parseCSV($filename);
        if (!empty($data)) {
            DB::table($table)->insert($data);
        }
    }
}
