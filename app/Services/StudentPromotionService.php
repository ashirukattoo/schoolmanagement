<?php namespace App\Services;

use App\Models\StudentModel;
use App\Models\ClassModel;
use App\Models\Stream;
use App\Models\StudentPromotionModel;
use Config\Database;

class StudentPromotionService {
    protected $students;
    protected $streams;
    protected $promotions;
    protected $db;

    public function __construct()
    {
        $this->students   = new StudentModel();
        $this->classes    = new ClassModel();
        $this->streams    = new Stream();
        $this->promotions = new StudentPromotionModel();
        $this->db         = Database::connect();
    }

    public function promoteLevel(string $level, string $academicYearId)   {
        //PREVENT DOUBLE PROMOTION
        $already = $this->promotions
            ->where('academic_year_id', $academicYearId)
            ->where('level', $level)
            ->countAllResults();

        if ($already > 0) {
            throw new \RuntimeException("Students already promoted for {$level} in this academic year.");
        }

        $this->db->transStart();

        // Get all active students in this level
        $students = $this->students
            ->select('students.*, classes.numeral, classes.cid, streams.sid')
            ->join('streams', 'streams.sid = students.stream_id')
            ->join('classes', 'classes.cid = streams.class_id')
            ->where('classes.level', $level)
            ->where('students.stuStatus', 'Active')
            ->findAll();

        foreach ($students as $stu) {

            // FINAL CLASS → COMPLETE
            if ($stu['numeral'] >= 4) {
                $this->completeStudent($stu, $academicYearId);
                continue;
            }

            $nextStream = $this->getNextStream($stu['sid']);

            // Update student current stream
            $this->students->update($stu['stuID'], [
                'stream_id' => $nextStream['sid']
            ]);

            // Log promotion
            $this->promotions->insert([
                'student_id'       => $stu['stuID'],
                'stream_id'        => $nextStream['sid'],
                'class_id'         => $nextStream['class_id'],
                'academic_year_id' => $academicYearId,
                'level'            => $level,
                'action'           => 'Promoted',
                'action_date'      => date('Y-m-d'),
            ]);
        }

        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            throw new \RuntimeException('Promotion failed, rolled back.');
        }
    }

    public function promoteStream($streamID){
        $students = $this->students->where('stream_id', $streamID)->findAll();
        if (!$students) return false;

        $currentStream = $this->streams->find($streamID);
        $currentClass  = $this->classes->find($currentStream['class_id']);

        $nextClass = $this->classes->nextClass($currentClass['cid']);

        foreach ($students as $stu) {

            // FINAL CLASS → Completed
            if (!$nextClass) {
                $this->promotions->insert([
                    'student_id' => $stu['stuID'],
                    'stream_id'  => $streamID,
                    'class_id'   => $currentClass['cid'],
                    'level'      => $currentClass['level'],
                    'action'     => 'Completed',
                    'action_date'=> date('Y-m-d'),
                ]);

                $this->students->update($stu['stuID'], [
                    'stuStatus' => 'Completed'
                ]);

                continue;
            }

            // SAME STREAM LETTER
            $nextStream = $this->streams
                ->findSameStream($nextClass['cid'], $currentStream['sName']);

            if (!$nextStream) {
                $nextStream = $this->streams->firstStream($nextClass['cid']);
            }

            // Update student
            $this->students->updateStream($stu['stuID'], $nextStream['sid']);

            // Log promotion
            $this->promotions->insert([
                'student_id' => $stu['stuID'],
                'stream_id'  => $nextStream['sid'],
                'class_id'   => $nextClass['cid'],
                'level'      => $nextClass['level'],
                'action'     => 'Promoted',
                'action_date'=> date('Y-m-d'),
            ]);
        }

        return true;
    }

    protected function getNextStream(int $currentStreamId): array    {
        $current = $this->streams->find($currentStreamId);

        return $this->streams
            ->where('class_id >', $current['class_id'])
            ->where('sName', $current['sName'])
            ->orderBy('class_id', 'ASC')
            ->first();
    }

    protected function completeStudent(array $stu, string $ay)    {
        $this->students->update($stu['stuID'], [
            'stuStatus' => 'Completed'
        ]);

        $this->promotions->insert([
            'student_id'       => $stu['stuID'],
            'stream_id'        => $stu['sid'],
            'class_id'         => $stu['cid'],
            'academic_year_id' => $ay,
            'level'            => 'O-level',
            'action'           => 'Completed',
            'action_date'      => date('Y-m-d'),
        ]);
    }
}
