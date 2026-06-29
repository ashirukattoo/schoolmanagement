<?php

namespace App\Models;

use CodeIgniter\Model;

class RawResultModel extends Model{
    protected $table      = 'rawresults';
    protected $primaryKey = 'raID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields = [
        'exam_id',
        'student_id',
        'subject_id',
        'raScore',
        'raGrade',
        'raCreated',
        'raCreatedBy',
        'raUpdated',
        'raStatus'
    ];

    // Automatically handle created/updated timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'raCreated';
    protected $updatedField  = 'raUpdated';

    public function add_score($data=null){
        $sql = "INSERT INTO rawresults(exam_id, student_id, subject_id, raScore, raGrade, raCreated, raCreatedBy, raStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
        if($this->db->query($sql, [
            $data['exam'],
            $data['student'],
            $data['subject'],
            $data['score'],
            $data['grade'],
            $data['date'],
            $data['creator'],
            $data['status']
        ])){
            return true;
        }else{
            return false;
        }
    }
    public function get_my_result($exam, $student=null){
        if ($student !== null) {
            $builder = $this->db->table('rawresults rr')
                        ->select('exam_id, rr.subject_id, student_id, subName as subject, subShort, isMandatory, raScore as score, raGrade as grade, raStatus as status')
                        ->join('subjects sb', 'sb.subID = rr.subject_id')
                        ->join('subject_streams ss', 'ss.subject_id = sb.subID')
                        ->where('rr.exam_id', $exam)
                        ->where('rr.student_id', $student)
                        ->orderBy('rr.raScore', 'DESC')
                        ->orderBy('sb.subName', 'ASC');
            return $builder->get()->getResultArray();
        }else{
            $builder = $this->db->table('rawresults rr')
                        ->select('exam_id, rr.subject_id, student_id, subName as subject, subShort, isMandatory, raScore as score, raGrade as grade, raStatus as status')
                        ->join('subjects sb', 'sb.subID = rr.subject_id')
                        ->join('subject_streams ss', 'ss.subject_id = sb.subID')
                        ->where('rr.exam_id', $exam)
                        ->orderBy('sb.subName', 'ASC');
            return $builder->get()->getResultArray();
        }
    }

    public function gradePerSubject($id){
        $builder = $this->db->table('rawresults ra')
                  ->select('subName as subject, raGrade as grade, count(student_id) as num, (select count(distinct student_id) from rawresults where exam_id="'.$id.'") as sat')
                  ->join('subjects sb', 'ra.subject_id = sb.subID')
                  ->where('exam_id', $id)
                  ->groupBy('subName, raGrade')
                  ->orderBy('subName', 'ASC')
                  ->orderBy('raGrade', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function scoredGrade($id){
        $builder = $this->db->table('rawresults r')
                  ->select('subject_id,  subName as somo, subCode as code, subShort as short, raGrade, count(*) AS number')
                  ->join('subjects sb', 'sb.subID=r.subject_id')
                  ->where('exam_id', $id)
                  ->groupBy(['r.subject_id', 'sb.subName', 'sb.subCode', 'sb.subShort', 'r.raGrade'])
                  ->orderBy('subName', 'ASC')
                  ->orderBy('raGrade', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function countDoneExam($id, $subId){
        $sql = "SELECT  count(student_id) AS total FROM rawresults r JOIN subjects sb on sb.subID = r.subject_id WHERE exam_id = ? AND r.subject_id =? ; ";

        $query = $this->db->query($sql, [$id, $subId])->getRow();

        return $query ? $query->total : null;   // direct value
    }

}
