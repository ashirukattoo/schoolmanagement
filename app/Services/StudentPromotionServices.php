<?php

namespace App\Services;

use Config\Database;
use RuntimeException;

class StudentPromotionService{
    protected $db;
    public function __construct() {
        $this->db = Database::connect();
    }
    //Promote single student
    public function promoteStudent(int $studentId): array   {
        $this->db->transBegin();
        try {
            $student = $this->db->table('students')
                ->where('stuID', $studentId)
                ->get()
                ->getRow();
            if (!$student) {
                throw new RuntimeException('Student not found.');
            }

            $currentStream = $this->db->table('streams')
                ->where('sid', $student->stream_id)
                ->get()
                ->getRow();

            if (!$currentStream) {
                throw new RuntimeException('Current stream not found.');
            }

            $currentClass = $this->db->table('classes')
                ->where('cid', $currentStream->class_id)
                ->get()
                ->getRow();

            if (!$currentClass) {
                throw new RuntimeException('Current class not found.');
            }

            /*
             * Find next class
             */
            $nextClass = $this->db->table('classes')
                ->where('numeral', $currentClass->numeral + 1)
                ->get()
                ->getRow();

            /*
             * Graduate if no next class
             */
            if (!$nextClass) {

                $this->db->table('students')
                    ->where('stuID', $studentId)
                    ->update([
                        'stuStatus' => 'Graduated'
                    ]);

                $this->db->transCommit();

                return [
                    'status'  => true,
                    'message' => 'Student graduated.'
                ];
            }

            /*
             * Find stream with same name in next class
             */
            $nextStream = $this->db->table('streams')
                ->where('class_id', $nextClass->cid)
                ->where('sName', $currentStream->sName)
                ->get()
                ->getRow();

            /*
             * Create stream automatically if not found
             */
            if (!$nextStream) {
                $this->db->table('streams')->insert([
                    'class_id' => $nextClass->cid,
                    'sName'    => $currentStream->sName,
                    'sStatus'  => 'Active',
                    'sCreated' => date('Y-m-d H:i:s')
                ]);

                $nextStreamId = $this->db->insertID();
                $nextStream = $this->db->table('streams')
                    ->where('sid', $nextStreamId)
                    ->get()
                    ->getRow();
            }

            /*
             * Activate stream if inactive
             */
            if ($nextStream->sStatus !== 'Active') {
                $this->db->table('streams')
                    ->where('sid', $nextStream->sid)
                    ->update([
                        'sStatus'  => 'Active',
                        'sUpdated' => date('Y-m-d H:i:s')
                    ]);
            }

            /*
             * Check active students already exist
             */
            $activeStudents = $this->db->table('students')
                ->where('stream_id', $nextStream->sid)
                ->where('stuStatus', 'Active')
                ->countAllResults();

            if ($activeStudents > 0) {

                throw new RuntimeException(
                    "Target stream already contains active students."
                );
            }

            /*
             * Log promotion
             */
            $this->db->table('promotions')->insert([
                'student_id'      => $student->stuID,
                'from_class_id'   => $currentClass->cid,
                'from_stream_id'  => $currentStream->sid,
                'to_class_id'     => $nextClass->cid,
                'to_stream_id'    => $nextStream->sid,
                'promotion_type'  => 'Individual',
                'academic_year'   => date('Y'),
                'promoted_by'     => session('user_id') ?? 1,
                'status'          => 'Completed'
            ]);

            /*
             * Move student
             */
            $this->db->table('students')
                ->where('stuID', $student->stuID)
                ->update([
                    'stream_id' => $nextStream->sid
                ]);

            if ($this->db->transStatus() === false) {
                throw new RuntimeException('Transaction failed.');
            }

            $this->db->transCommit();

            return [
                'status' => true,
                'message' => 'Student promoted successfully.',
                'student_id' => $student->stuID,
                'from_stream' => $currentStream->sid,
                'to_stream' => $nextStream->sid
            ];

        } catch (\Throwable $e) {

            $this->db->transRollback();

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Promote all students in one stream
     */
    public function promoteStream(int $streamId): array
    {
        $students = $this->db->table('students')
            ->where('stream_id', $streamId)
            ->where('stuStatus', 'Active')
            ->get()
            ->getResult();

        $success = 0;
        $failed  = 0;

        foreach ($students as $student) {

            $result = $this->promoteStudent($student->stuID);

            if ($result['status']) {
                $success++;
            } else {
                $failed++;
            }
        }

        return [
            'status'  => true,
            'success' => $success,
            'failed'  => $failed
        ];
    }

    /**
     * Promote all streams in class
     */
    public function promoteClass(int $classId): array
    {
        $streams = $this->db->table('streams')
            ->where('class_id', $classId)
            ->get()
            ->getResult();

        $promoted = 0;

        foreach ($streams as $stream) {

            $result = $this->promoteStream($stream->sid);

            $promoted += $result['success'] ?? 0;
        }

        return [
            'status' => true,
            'students_promoted' => $promoted
        ];
    }

    /**
     * Promote all classes within level
     */
    public function promoteLevel(int $level): array
    {
        $classes = $this->db->table('classes')
            ->where('level', $level)
            ->orderBy('numeral', 'DESC')
            ->get()
            ->getResult();

        $total = 0;

        foreach ($classes as $class) {

            $result = $this->promoteClass($class->cid);

            $total += $result['students_promoted'] ?? 0;
        }

        return [
            'status' => true,
            'students_promoted' => $total
        ];
    }
}