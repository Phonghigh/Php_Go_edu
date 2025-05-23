<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class ScoreController extends Controller
{
    public function show($regNo)
    {
        $score = StudentScore::where('sbd', $regNo)->first();

        if (!$score) {
            return response()->json(['message' => 'Student score not found'], 404);
        }

        return response()->json($score);
    }
    
    public function levels()
    {
        $rows = DB::table('subject_level_stats')
            ->select('subject','level','count')
            ->orderBy('subject')
            ->orderBy('level')
            ->get();

        // Group theo subject
        $report = [];
        foreach ($rows as $row) {
            $report[$row->subject][$row->level] = $row->count;
        }

        return response()->json($report);
    }


    /**
     * Xây dựng report gốc: chỉ 1 truy vấn duy nhất với SUM(CASE …)
     */
    private function buildLevelsReport(): array
    {
        // Danh sách các môn cần báo cáo
        $subjects = [
            'toan','ngu_van','ngoai_ngu',
            'vat_li','hoa_hoc','sinh_hoc',
            'lich_su','dia_li','gdcd'
        ];

        // Tạo mảng các biểu thức selectRaw
        $selects = [];
        foreach ($subjects as $subj) {
            $selects[] = "SUM(CASE WHEN {$subj} >= 8         THEN 1 ELSE 0 END) AS {$subj}_ge8";
            $selects[] = "SUM(CASE WHEN {$subj} >= 6 AND {$subj} < 8  THEN 1 ELSE 0 END) AS {$subj}_6_8";
            $selects[] = "SUM(CASE WHEN {$subj} >= 4 AND {$subj} < 6  THEN 1 ELSE 0 END) AS {$subj}_4_6";
            $selects[] = "SUM(CASE WHEN {$subj} < 4          THEN 1 ELSE 0 END) AS {$subj}_lt4";
        }

        // Chạy truy vấn duy nhất, đảm bảo selectRaw không rỗng
        $row = DB::table('student_scores')
            ->selectRaw(implode(",\n", $selects))
            ->first();

        // Chuyển kết quả thành cấu trúc dễ chart
        $report = [];
        foreach ($subjects as $subj) {
            $report[$subj] = [
                '>=8'  => (int) $row->{"{$subj}_ge8"},
                '6-<8' => (int) $row->{"{$subj}_6_8"},
                '4-<6' => (int) $row->{"{$subj}_4_6"},
                '<4'   => (int) $row->{"{$subj}_lt4"},
            ];
        }

        return $report;
    }

        public function top10(Request $request)
{
    $group = $request->query('group', 'A'); // Mặc định là khối A nếu không truyền group

    // Xác định các môn cần tính theo từng khối
    $groupSubjects = [
        'A' => ['toan', 'vat_li', 'hoa_hoc'],
        'B' => ['toan', 'hoa_hoc', 'sinh_hoc'],
        'C' => ['ngu_van', 'lich_su', 'dia_li'],
        'D' => ['toan', 'ngu_van', 'ngoai_ngu'],
    ];

    // Nếu group không hợp lệ thì trả lỗi
    if (!array_key_exists($group, $groupSubjects)) {
        return response()->json(['error' => 'Invalid group'], 400);
    }

    $subjects = $groupSubjects[$group];

    // Tạo biểu thức tính tổng điểm
    $totalExpr = implode(' + ', array_map(fn($s) => "COALESCE($s, 0)", $subjects));

    // Truy vấn top 10
    $scores = StudentScore::selectRaw("*, ($totalExpr) as total")
        ->orderByDesc('total')
        ->limit(10)
        ->get();

    return response()->json($scores);
}


}


