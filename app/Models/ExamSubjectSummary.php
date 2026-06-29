<?php

namespace App\Models;
use CodeIgniter\Model;

class ExamSubjectSummary extends Model{
    protected $table = 'exam_subject_summary';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields = [
        'exam_id',
        'reg',
        'subject_id',
        'seat',
        'abs',
        'grades_json',
        'pass',
        'pass_percent',
        'gpa',
        'grade',
        'level',
        'created_at' ];
       

    //1. Compile & Store Summary
    public function compileSubjectSummary($exam_id, $level) {
        // Clear old summary (avoid duplicates)
        $this->db->table('exam_subject_summary')
                 ->where('exam_id', $exam_id)
                 ->delete();

        // Define grade structure dynamically
        $gradeList = (strtolower($level) == 'a-level') 
            ? ['A','B','C','D','E','S','F'] 
            : ['A','B','C','D','F'];

        // Build dynamic SQL for grades
        $gradeSQL = "";
        foreach ($gradeList as $g) {
            $gradeSQL .= "SUM(CASE WHEN raGrade='$g' THEN 1 ELSE 0 END) AS $g, ";
        }
        $sql = "
            SELECT 
                subject_id,
                COUNT(*) as seat,
                $gradeSQL
                AVG(raScore) as avg_score
            FROM rawresults
            WHERE exam_id = ?
            GROUP BY subject_id
        ";

        $results = $this->db->query($sql, [$exam_id])->getResultArray();

        foreach ($results as $row) {

            // Build grades JSON dynamically
            $grades = [];
            foreach ($gradeList as $g) {
                $grades[$g] = $row[$g] ?? 0;
            }

            $seat = $row['seat'];

            // PASS = all except F
            $pass = $seat - ($grades['F'] ?? 0);

            $pass_percent = $seat > 0 ? ($pass / $seat) * 100 : 0;

            // GPA calculation (simple weight system)
            $pointsMap = (strtolower($level)==='a-level')?[
                'A'=>1,'B'=>2,'C'=>3,'D'=>4,'E'=>5,'S'=>6,'F'=>7] : 
                ['A'=>1,'B'=>2,'C'=>3,'D'=>4,'F'=>5];

            $totalPoints = 0;
            foreach ($grades as $g => $count) {
                $points = $pointsMap[$g] ?? 0;
                $totalPoints += ($points * $count);
            }

            $gpa = $seat > 0 ? $totalPoints / $seat : 0;

            $grade = $this->calculateGpaGrade($gpa, $level);

            // Insert
            $this->db->table('exam_subject_summary')->insert([
                'exam_id' => $exam_id,
                'subject_id' => $row['subject_id'],
                'grades_json' => json_encode($grades),
                'seat' => $seat,
                'pass' => $pass,
                'pass_percent' => $pass_percent,
                'gpa' => $gpa,
                'grade' => $grade,
                'level' => $level
            ]);
        }
    }

    //2. Retrieve Summary
    public function getSummary($exam_id)
    {
        $data = $this->db->table('exam_subject_summary s')
            ->select('s.*, sb.subName, sb.subCode')
            ->join('subjects sb', 'sb.subID = s.subject_id')
            ->where('s.exam_id', $exam_id)
            ->orderBy('subName', 'ASC')
            ->get()
            ->getResultArray();

        // Decode JSON
        foreach ($data as &$row) {
            $decoded = json_decode($row['grades_json'], true);

            // Ensure it's always an array
            $row['grades'] = is_array($decoded) ? $decoded : [];
        }

        return $data;
    }

    function calculateGpaGrade($gpa, $level) {
        $level = strtolower($level);
        switch ($level) {
             case 'a-level':
                 if ($gpa>=1 && $gpa < 2) return 'A';
                 elseif ($gpa>=2 && $gpa < 3) return 'B';
                 elseif ($gpa>=3 && $gpa < 4) return 'C';
                 elseif ($gpa>=4 && $gpa < 5) return 'D';
                 elseif ($gpa>=5 && $gpa < 6) return 'E';
                 elseif ($gpa>=6 && $gpa < 6.5) return 'S';
                 else return 'F';
                 break;
             
             default:
                 if ($gpa>=1 && $gpa < 2) return 'A';
                 elseif ($gpa>=2 && $gpa < 3) return 'B';
                 elseif ($gpa>=3 && $gpa < 4) return 'C';
                 elseif ($gpa>=4 && $gpa < 4.6) return 'D';
                 elseif ($gpa>=4.6 && $gpa <=5) return 'F';
                 else return 'F';
                 break;
         } 
    }
}