<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamAnalyticsModel extends Model{
    protected $table = 'exam_analytics';
    protected $primaryKey = 'eaid';

    protected $allowedFields = [
        'exam_id',
        'registered',
        'sat',
        'absent',
        'passed',
        'failed',
        'gpa',
        'gpa_grade',
        'pass_percent',
        'grade_summary',
        'subject_summary',
        'eastatus',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = false;

    /* =========================
       BASIC CRUD
    ==========================*/

    public function createAnalytics($data)
    {
        return $this->insert($data);
    }

    public function updateAnalytics($exam_id, $data) {
        return $this->where('exam_id', $exam_id)
                    ->set($data)
                    ->update();
    }

    public function getAnalytics($exam_id) {
        return $this->where('exam_id', $exam_id)
                    ->first();
    }

    public function deleteAnalytics($exam_id) {
        return $this->where('exam_id', $exam_id)
                    ->set(['deleted_at' => date('Y-m-d H:i:s'), 'eastatus' => 'deleted'])
                    ->update();
    }

    public function changeStatus($exam_id, $status) {
        return $this->where('exam_id', $exam_id)
                    ->set(['eastatus' => $status])
                    ->update();
    }
}