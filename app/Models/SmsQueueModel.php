<?php

namespace App\Models;

use CodeIgniter\Model;

class SmsQueueModel extends Model
{
    protected $table = 'sms_queue';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'student_id',
        'phone',
        'message',
        'status',
        'retries'
    ];

    protected $useTimestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Queue New SMS
    |--------------------------------------------------------------------------
    */

    public function queueSMS($data)
    {
        return $this->insert($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Pending SMS
    |--------------------------------------------------------------------------
    */

    public function getPendingSMS($limit = 20)
    {
        return $this->where('status', 'Pending')
                    ->limit($limit)
                    ->findAll();
    }

    /*
    |--------------------------------------------------------------------------
    | Update SMS Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus($id, $data)
    {
        return $this->update($id, $data);
    }

}