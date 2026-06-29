<?php

namespace App\Models;

use CodeIgniter\Model;

class SmsLogModel extends Model
{
    protected $table = 'sms_logs';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'student_id',
        'phone',
        'message',
        'status',
        'response',
        'sent_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | Save SMS Log
    |--------------------------------------------------------------------------
    */

    public function saveLog($data)
    {
        return $this->insert($data);
    }

    /*
    |--------------------------------------------------------------------------
    | SMS History
    |--------------------------------------------------------------------------
    */

    public function getHistory($limit = 100)
    {
        return $this->orderBy('id', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

}