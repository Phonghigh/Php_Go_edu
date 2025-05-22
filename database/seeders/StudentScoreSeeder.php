<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentScore;
use League\Csv\Reader;

class StudentScoreSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/scores.csv');
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // Dòng đầu là header

        $records = $csv->getRecords();
        $batchSize = 1000;
        $batch = [];

        foreach ($records as $record) {
            $batch[] = [
                'sbd' => $record['sbd'],
                'toan' => $record['toan'] ?: null,
                'ngu_van' => $record['ngu_van'] ?: null,
                'ngoai_ngu' => $record['ngoai_ngu'] ?: null,
                'vat_li' => $record['vat_li'] ?: null,
                'hoa_hoc' => $record['hoa_hoc'] ?: null,
                'sinh_hoc' => $record['sinh_hoc'] ?: null,
                'lich_su' => $record['lich_su'] ?: null,
                'dia_li' => $record['dia_li'] ?: null,
                'gdcd' => $record['gdcd'] ?: null,
                'ma_ngoai_ngu' => $record['ma_ngoai_ngu'] ?: null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                StudentScore::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            StudentScore::insert($batch);
        }
    }
}
