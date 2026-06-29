<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'student_id',
        'stream_id',
        'session',
        'date',
        'term_id',
        'status'
    ];

    protected $useTimestamps = false;

    /**
     * Get attendance for a specific date/session/stream
     */
    public function getAttendance($stream_id, $date, $session)
    {
        return $this->where([
            'stream_id' => $stream_id,
            'date' => $date,
            'session' => $session
        ])->findAll();
    }

    /**
     * Save or update attendance (based on UNIQUE KEY)
     */
    public function saveBatchAttendance($data)
    {
        foreach ($data as $row) {
            $this->save($row); // auto insert/update
        }
    }
}