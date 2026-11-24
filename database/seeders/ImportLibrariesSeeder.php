<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportLibrariesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Expects CSV at storage/app/libraries_combined_alwar_jaipur.csv
     */
    public function run()
    {
        $path = storage_path('app/libraries_combined_alwar_jaipur.csv');
        if (!file_exists($path)) {
            $this->command->error("CSV file not found: {$path}");
            return;
        }

        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);

        $headers = [];
        $rowCount = 0;
        $batch = [];
        $batchSize = 200;

        foreach ($file as $idx => $row) {
            if ($row === null) continue;
            // Skip empty rows
            if (count($row) === 1 && $row[0] === null) continue;
            if ($idx === 0) {
                $headers = array_map('trim', $row);
                continue;
            }
            if (!$row || count(array_filter($row, function ($c) { return $c !== null && $c !== ''; })) === 0) continue;

            $data = [];
            foreach ($headers as $i => $h) {
                $key = $h ?: "col_{$i}";
                $value = isset($row[$i]) ? trim($row[$i]) : null;
                $data[$key] = $value;
            }

            // Map common fields to columns expected in `libraries` table
            $insert = [
                'name' => $data['name'] ?? null,
                'city' => $data['query_city'] ?? ($data['city'] ?? null),
                'street' => $data['street'] ?? null,
                'housenumber' => $data['housenumber'] ?? null,
                'postcode' => $data['postcode'] ?? null,
                'lat' => $data['lat'] ?? null,
                'lon' => $data['lon'] ?? null,
                'phone' => $data['phone'] ?? null,
                'website' => $data['website'] ?? null,
                'opening_hours' => $data['opening_hours'] ?? null,
                'operator' => $data['operator'] ?? null,
                'description' => $data['description'] ?? null,
                'osm_type' => $data['osm_type'] ?? null,
                'osm_id' => $data['osm_id'] ?? null,
                'wikidata' => $data['wikidata'] ?? null,
                'wikipedia' => $data['wikipedia'] ?? null,
                'source' => $data['source'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // If your table has different columns, adjust the mapping above.
            $batch[] = $insert;
            $rowCount++;

            if (count($batch) >= $batchSize) {
                DB::table('libraries')->insert($batch);
                $batch = [];
                $this->command->info("Inserted {$rowCount} rows...");
            }
        }

        if (count($batch) > 0) {
            DB::table('libraries')->insert($batch);
            $this->command->info("Inserted total {$rowCount} rows.");
        }

        $this->command->info('Import complete.');
    }
}
