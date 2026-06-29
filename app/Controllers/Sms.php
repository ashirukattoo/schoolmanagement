<?php

namespace App\Controllers;

use App\Models\SmsQueueModel;
use App\Models\SmsLogModel;
use App\Models\StudentModel;


class Sms extends BaseController{
    protected $smsQueueModel;
    protected $smsLogModel;
    protected $studentModel;
    protected $db;
    protected $smsService;

    public function __construct(){
        helper('sms');
        $this->smsQueueModel = new SmsQueueModel();
        $this->smsLogModel   = new SmsLogModel();
        $this->studentModel  = new StudentModel();
        $this->db = \Config\Database::connect();
        $this->smsService = \Config\Services::smsService();
    }

    //Compose SMS Page
    public function index(){
        return view('sms/compose');
    }

    // Queue Single SMS

    public function send() {
        $phone = $this->request->getPost('phone');
        $message = $this->request->getPost('message');

        $this->smsQueueModel->queueSMS([
            'phone' => $phone,
            'message' => $message,
            'status' => 'Pending',
            'retries' => 0
        ]);
        return redirect()->back()->with('success', 'SMS queued successfully');
    }

    // Process Queue
    public function processQueue() {
        $messages = $this->smsQueueModel->getPendingSMS();
        foreach ($messages as $sms)     {
            $response = send_sms_api($sms['phone'], $sms['message']);
            $status = $response ? 'Sent' : 'Failed';

            //Update Queue
            $this->smsQueueModel->updateStatus(
                $sms['id'],
                [
                    'status' => $status,
                    'retries' => $sms['retries'] + 1
                ]
            );

            // Save Log
            $this->smsLogModel->saveLog([
                'student_id' => $sms['student_id'] ?? null,
                'phone'      => $sms['phone'],
                'message'    => $sms['message'],
                'status'     => $status,
                'response'   => $response,
                'sent_by'    => session()->get('user_id') ?? 1
            ]);
        }

        echo "Queue Processed Successfully";
    }

    /*
    |--------------------------------------------------------------------------
    | SMS History
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $data['sms'] = $this->smsLogModel->getHistory();

        return view('sms/history', $data);
    }

    //Bulk SMS By Stream
    public function sendStreamSMS(){
        $stream_id = $this->request->getPost('stream_id');
        $message   = $this->request->getPost('message');

        $students = $this->studentModel
                        ->where('stream_id', $stream_id)
                        ->findAll();

        foreach($students as $student)  {
            if(!empty($student['guardian_phone'])) {
                $this->smsQueueModel->queueSMS([
                    'student_id' => $student['id'],
                    'phone'      => $student['guardian_phone'],
                    'message'    => $message,
                    'status'     => 'Pending',
                    'retries'    => 0
                ]);
            }
        }

        return redirect()->back()
                         ->with('success', 'Bulk SMS queued successfully');
    }

    public function examResults(){
        $data = [
            'pageTitle' => "Exam Results - SMS",
            'classes'   => $this->classModel->findAll(),
            'exams'     => $this->examModel->list()
        ];
        return view('admin/sms/examination_results', $data);
    }
    public function fetchExamRecipients(){
        $class_id = $this->request->getPost('class_id');
        $exam_id  = $this->request->getPost('exam_id'); 
        //GET COMPILED RESULTS
        $results = $this->compiledResultsModel
                      ->getResultByClass($class_id, $exam_id);
        $rows = [];
        $i = 1;
        foreach($results as $row){
            // SKIP STUDENTS WITHOUT CONTACT
            if(empty($row['guardian_phone'])){
                continue;
            }
            //DECODE SUBJECT RESULTS
            $resultsJSON = json_decode($row['crResults'], true);
            $subjectSummary = '';
            if(isset($resultsJSON['subjects']) && is_array($resultsJSON['subjects'])){
                $subjects = [];
                foreach($resultsJSON['subjects'] as $subject) {
                    $subjects[] = $subject['subject'].':'.number_format($subject['score'], 0).'-'.$subject['grade'];
                }
                $subjectSummary = implode(', ', $subjects);
            }
            // SMS CONTENT
            $message = "Ndg mzazi wa ".$row['student'].".\n". "Matokeo ya Mtihani wa Muhula wa I ya  mwanao nikama ifuatavyo: Masomo: ".$subjectSummary.", Wastani:".round($row['crAverage'], 2)."%\n. Div:".$row['crDivision']."-".$row['crPoints'];
            //PACK SMS CONTENT IN ARRAY
            $rows[] = [
                'sn' => $i++,'student' => $row['student'],
                'phone' => $row['guardian_phone'],'message' => $message,
                'student_id' => $row['student_id']
            ];
        }
        return $this->response->setJSON($rows);
    }
    public function queueExamResultSMS(){
        $data = $this->request->getJSON(true);
        $messages = $data['messages'] ?? [];
        if(empty($messages)){
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No messages found'
            ]);
        }
        $queued = 0;
        foreach($messages as $msg){
            if(empty($msg['phone']) || empty($msg['message']) ) {
                continue;
            }
            $this->smsQueueModel->queueSMS([
                'student_id' => $msg['student_id'] ?? null,
                'phone' => trim($msg['phone']),
                'message' => trim($msg['message']),
                'status' => 'Pending',
                'retries' => 0
            ]);
            $queued++;
        }
        return $this->response->setJSON([
            'status' => true,
            'message' => $queued . ' SMS queued successfully'
        ]);
    }

    //Fetching Examination VIA AJAX for SMS QUEUE Form
    public function fetchClassExams(){
        $class_id = $this->request->getPost('class_id');

        $exams = $this->examModel
                      ->where('class_id', $class_id)
                      ->where('exStatus', 'current')
                      ->findAll();

        return $this->response->setJSON($exams);
    }
    //QUEUED SMS VIEW
    public function queue(){
        $data = [
            'pageTitle' => 'Queued SMS',
            'messages' => $this->smsQueueModel
                        ->orderBy('id', 'DESC')
                        ->findAll()
        ];
        return view('admin/sms/queue', $data);
    }
    //SEND SINGLE SMS
    public function sendSingle($id){
        $sms = $this->smsQueueModel->find($id);
        if(!$sms)  {
            return redirect()->back() ->with('error', 'SMS not found');
        }
        //  SEND SMS
        $response = send_sms_api($sms['phone'], $sms['message']);
        $status = $response ? 'Sent' : 'Failed';

        //UPDATE QUEUE
        $this->smsQueueModel->update($id, ['status'=>$status,'retries'=>$sms['retries'] + 1]);
        //SAVE LOG
        $this->smsLogModel->save([
            'student_id' => $sms['student_id'] ?? null,
            'phone' => $sms['phone'],
            'message' => $sms['message'],
            'status' => $status,
            'response' => is_string($response) ? $response : json_encode($response),
            'sent_by' => session()->get('user_id') ?? 1
        ]);
        return redirect()->back()->with('success', 'SMS processed');
    }
    //RETRY FAILED SMS
    public function retrySMS($id){
        $sms = $this->smsQueueModel->find($id);
        if(!$sms){
            return redirect()->back()->with('error', 'SMS not found');
        }
        //RESET STATUS
        $this->smsQueueModel->update($id, ['status' => 'Pending']);
        return redirect()->back()->with('success', 'SMS queued again');
    }

    public function bulkSMS(){
        $data = [
            'pageTitle' => 'Bulk SMS',
            'classes'   => $this->classModel->findAll()
        ];
        return view('sms/bulk_sms', $data);
    }

    public function fetchBulkRecipients(){
        $class_id = $this->request->getPost('class_id');
        $students = $this->studentModel->getByClass($class_id);
        $rows = [];
        foreach($students as $student){
            $full = $this->studentModel
                        ->find($student['id']);
            if(empty($full['guardian_phone'])) { continue; }
            $rows[] = [
                'student_id' => $student['id'],
                'name' => $student['name'],
                'phone' => $full['guardian_phone']
            ];
        }
        return $this->response->setJSON($rows);
    }

    public function queueBulkSMS(){
        $data = $this->request->getJSON(true);
        $message = $data['message'] ?? '';
        $recipients = $data['recipients'] ?? [];
        if(empty($message)){
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Message required'
            ]);
        }

        $queued = 0;
        foreach($recipients as $recipient)  {
            $this->smsQueueModel->insert([
                'student_id' => $recipient['student_id'],
                'phone' => $recipient['phone'],
                'message' => $message,
                'status' => 'Pending',
                'retries' => 0
            ]);
            $queued++;
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => $queued . ' SMS queued'
        ]);
    }

    //GENERAL METHODS FOR SENDING SMS
    public function sendSms($receiver, $message=''){
        //Send sms
    }

    public function checkPhoneValidity() {
        $phone = $this->request->getPost('phone');
        $valid = $this->smsService
                      ->checkPhoneValidity($phone);
        return $this->response->setJSON(['status' => $valid]);
    }

    public function testSMS(){
        $response = send_sms_api(
            '255758192801',
            'LSS SMS test message'
        );
        echo '<pre>';
        print_r($response);
        echo '</pre>';
    }
}