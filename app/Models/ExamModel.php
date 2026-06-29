<?php namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model{
    protected $table = 'exams';
    protected $primaryKey = 'exID';
    protected $allowedFields = [        
        'exName',  'class_id', 'term_id',
        'exSubjects',  'exCreated', 'exUpdated', 'exStatus'
    ];

    public function getLevel($id){
        $sql = "SELECT ay.ayLevel 
                FROM exams ex
                join terms t on t.tID = ex.term_id
                JOIN academicyears ay ON ay.ayID = t.ay_id
                WHERE ex.exID = ?";

        $query = $this->db->query($sql, [$id])->getRow();

        return $query ? $query->ayLevel : null;   // direct value
    }
    public function info($id){
        $sql = "SELECT exID, exName as exam, named as class, term_id, concat(ayName, '-Term ', tName ) as year FROM exams ex join classes cl on ex.class_id=cl.cid join terms t on t.tID = ex.term_id  join academicyears ay on ay.ayID = t.ay_id where exID =?;";
        $query = $this->db->query($sql, [$id])->getRowArray();
        return $query;
    }

    public function list($status='current'){
        $sql = "SELECT exID AS id, exName AS exam, cid, named AS class, exStatus AS state FROM exams ex JOIN classes cl ON ex.class_id=cl.cid WHERE exStatus=?";
        $query = $this->db->query($sql, [$status])->getResultArray();
        return $query;
    }

    public function getClassInfo($exam_id){
        $sql = "SELECT * FROM exams e join classes cl on cl.cid = e.class_id where e.exID = ?;";
        $query = $this->db->query($sql, [$exam_id])->getRowArray();
        return $query;
    }

    public function getAcadYear($id){
        $sql = "SELECT ay.ayName 
                FROM exams ex
                JOIN terms t on t.tID = ex.term_id
                JOIN academicyears ay ON ay.ayID = t.ay_id
                WHERE ex.exID = ?";

        $query = $this->db->query($sql, [$id])->getRow();

        return $query ? $query->ayName : null;   // direct value
    }
    

    public function add($data=null){
        $sql = "INSERT INTO exams(exID, exName, class_id, term_id, exSubjects, exCreated, exStatus) VALUES (?, ?, ?, ?, ?, ?, ?);";
        if($this->db->query($sql, [
            $data['id'],
            $data['name'],
            $data['class'],
            $data['term_id'],
            $data['subjects'],
            $data['created'],
            $data['status']
        ])){
            return true;
        }else{
            return false;
        }
    }

    public function getExam($status = 'all'){
        if ($status === 'all') {
            $builder = $this->db->table('exams ex')
                        ->select('ayID as yid, tName as term, exID as id, named as class, exName as exam, ayName as year, exStatus as status, cl.cid as cid')
                        ->join('terms t', 't.tID = ex.term_id')
                        ->join('academicyears ay', 'ay.ayID = t.ay_id')
                        ->join('classes cl', 'cl.cid = ex.class_id')
                        ->orderBy('ayName', 'ASC')
                        ->orderBy('named', 'ASC')
                        ->orderBy('exStatus', 'ASC');
            return $builder->get()->getResultArray();
        }else{
            $builder = $this->db->table('exams ex')
                        ->select('ayID as yid, tName as term, exID as id, named as class, exName as exam, ayName as year, exStatus as status, cl.cid as cid')
                        ->join('terms t', 't.tID = ex.term_id')
                        ->join('academicyears ay', 'ay.ayID = t.ay_id')
                        ->join('classes cl', 'cl.cid = ex.class_id')
                        ->where('ex.exStatus', $status)
                        ->orderBy('ayName', 'ASC')
                        ->orderBy('named', 'ASC')
                        ->orderBy('exStatus', 'ASC');
            return $builder->get()->getResultArray();
        }    
    }

    public function listExam($status = 'all'){
        if ($status === 'all') {
            $builder = $this->db->table('exams ex')
                        ->select('exID as id, tName as term, exID as id, named as class, exName as exam, ayName as year, exStatus as status, cl.cid as cid')
                        ->join('terms t', 't.tID = ex.term_id')
                        ->join('academicyears ay', 'ay.ayID = t.ay_id')
                        ->join('classes cl', 'cl.cid = ex.class_id')
                        ->orderBy('ayName', 'ASC')
                        ->orderBy('named', 'ASC')
                        ->orderBy('exStatus', 'ASC');
            return $builder->get()->getResultArray();
        }else{
            $builder = $db->table('exams ex')
                        ->select('ayID as yid, tName as term, exID as id, named as class, exName as exam, ayName as year, exStatus as status, cl.cid as cid')
                        ->join('terms t', 't.tID = ex.term_id')
                        ->join('academicyears ay', 'ay.ayID = t.ay_id')
                        ->join('classes cl', 'cl.cid = ex.class_id')
                        ->where('ex.exStatus', $status)
                        ->orderBy('ayName', 'ASC')
                        ->orderBy('named', 'ASC')
                        ->orderBy('exStatus', 'ASC');
            return $builder->get()->getResultArray();
        }    
    }
}