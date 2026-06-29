<?php namespace App\Models;

use CodeIgniter\Model;

class TourFeedbackModel extends Model
{
    protected $table = 'tour_feedbacks';
    protected $primaryKey = 'tfID';
    protected $allowedFields = [
        'student_id', 'gurdian_id', 'tfResponse', 'tfState',
        'employee_id', 'tfComment', 'tfStatus', 'tfCreated', 'tfUpdated'
    ];
}