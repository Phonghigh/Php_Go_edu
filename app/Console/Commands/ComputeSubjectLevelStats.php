<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class ComputeSubjectLevelStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:compute-subject-level';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subjects = ['toan','ngu_van','ngoai_ngu','vat_li','hoa_hoc','sinh_hoc','lich_su','dia_li','gdcd'];
        $levels = [
            'ge8'  => fn($q, $s) => $q->where($s, '>=', 8),
            '6-<8' => fn($q, $s) => $q->where($s, '>=', 6)->where($s, '<', 8),
            '4-<6' => fn($q, $s) => $q->where($s, '>=', 4)->where($s, '<', 6),
            'lt4'  => fn($q, $s) => $q->where($s, '<', 4),
        ];

        foreach ($subjects as $subject) {
            foreach ($levels as $key => $applyConditions) {
                // Tạo query và áp điều kiện
                $query = DB::table('student_scores');
                $query = $applyConditions($query, $subject);

                $count = $query->count();

                DB::table('subject_level_stats')->updateOrInsert(
                    ['subject' => $subject, 'level' => $key],
                    ['count' => $count, 'updated_at' => now()]
                );
            }
        }

        $this->info('Subject level stats recomputed.');
    }


}
