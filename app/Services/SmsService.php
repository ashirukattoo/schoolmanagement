<?php

namespace App\Services;

use App\Models\SmsQueueModel;
use App\Models\SmsLogModel;
use App\Models\StudentModel;
use App\Models\EmployeeModel;

class SmsService {
    protected $queueModel;
    protected $logModel;
    protected $studentModel;
    protected $empModel;

    public function __construct()  {
        helper('sms');
        $this->queueModel   = new SmsQueueModel();
        $this->logModel     = new SmsLogModel();
        $this->studentModel = new StudentModel();
        $this->empModel     = new EmployeeModel();
    }

    // Queue SMS
    public function queueSMS(string $phone, string $message, ?int $student_id = null){
        return $this->queueModel->insert([
            'student_id' => $student_id,
            'phone'      => $phone,
            'message'    => $message,
            'status'     => 'Pending',
            'retries'    => 0
        ]);
    }

    //Send SMS Immediately
    public function sendSMS(string $phone, string $message,?int $student_id = null) {
        $response = send_sms_api($phone, $message);
        $decoded = json_decode($response, true);
        $status = (
            isset($decoded['successful']) &&
            $decoded['successful'] == true
        ) ? 'Sent' : 'Failed';
        $this->logModel->insert([
            'student_id' => $student_id,
            'phone'      => $phone,
            'message'    => $message,
            'status'     => $status,
            'response'   => $response,
            'sent_by'    => session()->get('user_id') ?? 1
        ]);

        return [
            'status'   => $status,
            'response' => $decoded
        ];
    }

    // Send To Student
    public function sendToStudent(
        int $student_id,
        string $message,
        bool $queue = true
    )  {
        $student = $this->studentModel
                        ->find($student_id);

        if (!$student) {
            return false;
        }

        if (empty($student['guardian_phone'])) {
            return false;
        }

        if ($queue) {
            return $this->queueSMS(
                $student['guardian_phone'],
                $message,
                $student_id
            );
        }

        return $this->sendSMS(
            $student['guardian_phone'],
            $message,
            $student_id
        );
    }

    //Process Queue
    public function processQueue()   {
        $messages = $this->queueModel
                         ->where('status', 'Pending')
                         ->findAll();

        foreach ($messages as $sms){
            $result = $this->sendSMS(
                $sms['phone'],
                $sms['message'],
                $sms['student_id']
            );
            $this->queueModel->update(
                $sms['id'],
                [
                    'status'  => $result['status'],
                    'retries' => $sms['retries'] + 1
                ]
            );
        }
        return true;
    }
    /**
     * Format Tanzania phone numbers
     */
    public function formatPhoneNumber($phone)    {
        $phone = trim($phone);

        // Remove spaces
        $phone = str_replace(' ', '', $phone);

        // Remove +
        $phone = ltrim($phone, '+');

        // Format 07XXXXXXXX
        if (preg_match('/^0\d{9}$/', $phone)) {
            return '255' . substr($phone, 1);
        }

        // Already formatted 255XXXXXXXXX
        if (preg_match('/^255\d{9}$/', $phone)) {
            return $phone;
        }

        return false;
    }

    public function sendEmployeeWelcome($phone, $name, $role) {
        if (!$this->checkPhoneValidity($phone)) {
            return false;
        }

        $message =
            "Karibu LSS. {$name}, ".
            "akaunti yako imefunguliwa kama {$role}. ".
            "Tunakutakia kazi njema.";

        return $this->queueSMS([
            'phone' => $phone,
            'message' => $message,
            'status' => 'Pending',
            'retries' => 0
        ]);
    }

    /* Check if phone is valid   */
    public function checkPhoneValidity($phone)    {
        return $this->formatPhoneNumber($phone) !== false;
    }
}