<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StudentModel;
use App\Models\UserRoleModel;
use App\Models\ClassModel;   // optional future extension
use App\Models\ExamModel;    // optional future extension
use CodeIgniter\Controller;

class AdminUI extends Controller
{
    public function compile_results($exam_id){
            $db = \Config\Database::connect();

            // Step 1: Fetch all raw results for this exam
            $query = $db->query("
                SELECT student_id, subject_id, raGrade, raScore FROM rawresults
                WHERE exam_id = ? ORDER BY student_id ASC", 
                [$exam_id]);

            $results = $query->getResultArray();

            if (empty($results)) {
                return redirect()->back()->with('error', 'No results found for this exam.');
            }

            // Step 2: Group results by student
            $studentResults = [];
            foreach ($results as $row) {
                $studentResults[$row['student_id']][] = [
                    'subject_id' => $row['subject_id'],
                    'grade'      => strtoupper(trim($row['raGrade'])),
                    'score'      => $row['raScore']
                ];
            }

            // Step 3: Grade → Points (O-Level)
            $gradePoints = [
                'A' => 1,
                'B' => 2,
                'C' => 3,
                'D' => 4,
                'F' => 5,
            ];

            // Step 4: Division logic
            $compiled = [];
            foreach ($studentResults as $studentId => $subjects) {
                $points = [];

                // Convert grades to points
                foreach ($subjects as $s) {
                    $grade = $s['grade'];
                    if (isset($gradePoints[$grade])) {
                        $points[] = $gradePoints[$grade];
                    }
                }

                if (count($points) === 0) continue;

                // Step 5: Sort points ascending (best = lowest)
                sort($points);

                // Step 6: Take best seven subjects only
                $best7 = array_slice($points, 0, 7);
                $total = array_sum($best7);

                // Step 7: Determine Division
                if ($total >= 7 && $total <= 16) {
                    $division = 'I';
                } elseif ($total >= 17 && $total <= 21) {
                    $division = 'II';
                } elseif ($total >= 22 && $total <= 25) {
                    $division = 'III';
                } elseif ($total >= 26 && $total <= 33) {
                    $division = 'IV';
                } elseif ($total >= 34 && $total <= 35) {
                    $division = '0';
                } else {
                    $division = 'N/A';
                }

                // Step 8: Remarks
                switch ($division) {
                    case 'I': $remark = 'Excellent'; break;
                    case 'II': $remark = 'Very Good'; break;
                    case 'III': $remark = 'Average'; break;
                    case 'IV': $remark = 'Pass'; break;
                    case '0': $remark = 'Fail'; break;
                    default: $remark = 'No Division';
                }

                // Step 9: Push compiled result
                $compiled[] = [
                    'student_id' => $studentId,
                    'student_name' => $this->getStudentName($studentId),
                    'best7_points' => $best7,
                    'total_points' => $total,
                    'division' => $division,
                    'remark' => $remark
                ];
            }

            // Step 10: Sort by best division and total
            usort($compiled, function($a, $b) {
                return $a['total_points'] <=> $b['total_points'];
            });

            // Optional: save compiled summary in DB table (compiled_results) if you have one
            // Otherwise, display directly in view
            return view('admin/exams/compiled_results', [
                'pageTitle' => 'Compiled O-Level Results',
                'compiled' => $compiled
            ]);
        }
        public function compile_exam_results($exam_id, $class_id){
            helper('text');
            $user_id = session()->get('user_id');

            // Fetch students in class
            $students = $this->studentModel->getByClass($class_id);
            if (empty($students)) {
                return redirect()->back()->with('error', 'No students found for this class.');
            }

            $compiledData = [];

            foreach ($students as $stu) {
                $stuID = $stu['id'];

                // Get raw exam results for student
                $rawResults = $this->rawResultModel
                    ->where(['exam_id' => $exam_id, 'student_id' => $stuID])
                    ->findAll();

                if (empty($rawResults)) continue;

                $subjects = [];
                $totalPoints = 0;
                $totalScore = 0;

                foreach ($rawResults as $result) {
                    $subjectName = $this->subjectModel->find($result['subject_id'])['subName'] ?? 'Unknown';
                    $points = $this->gradeToPoints($result['raGrade']);

                    $subjects[] = [
                        'subject' => $subjectName,
                        'score'   => $result['raScore'],
                        'grade'   => $result['raGrade'],
                        'points'  => $points
                    ];

                    $totalPoints += $points;
                    $totalScore  += $result['raScore'];
                }

                // Sort ascending and pick best 7
                usort($subjects, fn($a, $b) => $a['points'] <=> $b['points']);
                $best7 = array_slice($subjects, 0, 7);

                $points = array_sum(array_column($best7, 'points'));
                $division = $this->getDivision($points);
                $remark   = $this->remark($division, 1, 'division');

                // ====== NEW: Average and Total =======
                $avgScore = round($totalScore / count($rawResults), 2);

                // Build compile record as JSON
                $crResult = [
                    'subjects'  => $subjects,
                    'points'        => $points,
                    'division'      => $division,
                    'remark'        => $remark,
                    'total'   => $totalScore,
                    'average' => $avgScore
                ];


                $compiledData[] = [
                    'exam_id'       => $exam_id,
                    'student_id'    => $stuID,
                    'crResults'     => json_encode($crResult),
                    'crCreated'     => date('Y-m-d H:i:s'),
                    'crCompiledBy'  => $user_id,
                    'raStatus'      => 'compiled'
                ];
            }

            // Clear existing compiled data for same exam/class to avoid duplication
            $db = \Config\Database::connect();

            // Step 1: Get all student IDs in that class
            $studentIDs = $db->table('students s')
                ->select('s.stuID')
                ->join('streams st', 'st.sid = s.stream_id')
                ->where('st.class_id', $class_id)
                ->get()
                ->getResultArray();

            $ids = array_column($studentIDs, 'stuID');

            // Step 2: Delete compiled results for that exam + those students
            if (!empty($ids)) {
                $this->compiledResultsModel
                    ->where('exam_id', $exam_id)
                    ->whereIn('student_id', $ids)
                    ->delete();
            }

            // Step 3: Insert new compiled results
            if (!empty($compiledData)) {
                $this->compiledResultsModel->insertBatch($compiledData);

                //Step 4: Update corresponding rawresults as compiled
                $this->rawResultModel
                    ->where('exam_id', $exam_id)
                    ->whereIn('student_id', $ids)
                    ->set(['raStatus' => 'compiled'])
                    ->update();
            }

            $this->examModel
                ->where('exID', $exam_id)
                ->set(['exStatus' => 'current'])
                ->update();

            return redirect()->back()->with('success', 'Results compiled and saved successfully!');
        }
        public function compile_exam_results($exam_id, $class_id){
            helper('text');
            $user_id = session()->get('user_id');

            // Fetch students in class
            $students = $this->studentModel->getByClass($class_id);
            if (empty($students)) {
                return redirect()->back()->with('error', 'No students found for this class.');
            }

            $compiledData = [];

            foreach ($students as $stu) {
                $stuID = $stu['id'];

                // Get raw exam results for student
                $rawResults = $this->rawResultModel->get_my_result($exam_id, $stuID);
                
                if (empty($rawResults)) continue;

                $subjects = [];
                $totalPoints = 0;

                foreach ($rawResults as $result) {
                    $points = $this->gradeToPoints($result['grade']);

                    $subjects[] = [
                        'subject' => $result['subject'],
                        'score'   => $result['score'],
                        'grade'   => $result['grade'],
                        'points'  => $points
                    ];

                    $totalPoints += $points;
                }

                // Sort ascending and pick best 7
                usort($subjects, fn($a, $b) => $a['points'] <=> $b['points']);
                $best7 = array_slice($subjects, 0, 7);
                $points = array_sum(array_column($best7, 'points'));

                // Determine division and remark
                $division = $this->getDivision($points);
                $remark = $this->remark($division, 1, 'division');

                // Build compile record as JSON
                $crResult = [
                    'subjects'  => $best7,
                    'points'    => $points,
                    'division'  => $division,
                    'remark'    => $remark
                ];

                $compiledData[] = [
                    'exam_id'       => $exam_id,
                    'student_id'    => $stuID,
                    'crResults'     => json_encode($crResult),
                    'crCreated'     => date('Y-m-d H:i:s'),
                    'crCompiledBy'  => $user_id,
                    'raStatus'      => 'compiled'
                ];
            }

            // Clear existing compiled data for same exam/class to avoid duplication
            $db = \Config\Database::connect();

            // Step 1: Get all student IDs in that class
            $studentIDs = $db->table('students s')
                ->select('s.stuID')
                ->join('streams st', 'st.sid = s.stream_id')
                ->where('st.class_id', $class_id)
                ->get()
                ->getResultArray();

            $ids = array_column($studentIDs, 'stuID');

            // Step 2: Delete compiled results for that exam + those students
            if (!empty($ids)) {
                $this->compiledResultsModel
                    ->where('exam_id', $exam_id)
                    ->whereIn('student_id', $ids)
                    ->delete();
            }

            // Step 3: Insert new compiled results
            if (!empty($compiledData)) {
                $this->compiledResultsModel->insertBatch($compiledData);

                //Step 4: Update corresponding rawresults as compiled
                $this->rawResultModel
                    ->where('exam_id', $exam_id)
                    ->whereIn('student_id', $ids)
                    ->set(['raStatus' => 'compiled'])
                    ->update();
            }
            $this->examModel
                    ->where('exID', $exam_id)
                    ->set(['exStatus' => 'current'])
                    ->update();

            return redirect()->back()->with('success', 'Results compiled and saved successfully!');
        }
}