<?php
namespace App\Models;

use CodeIgniter\Model;

class CompiledResultsModel extends Model{
    protected $table            = 'compiledresults';
    protected $primaryKey       = 'crID';
    protected $allowedFields = [
        'exam_id',
        'student_id',
        'crResults',
        'crTotal',
        'crAverage',
        'crPoints',
        'crDivision',
        'crPosition',
        'crRemarks',
        'crMaoni',
        'crCreated',
        'crCompiledBy',
        'raUpdated',
        'raStatus'
    ];

    public function getResultByClass($class_id, $exam_id) {
        $results = $this->db->table('compiledresults cr')
                          ->select('cr.*, s.guardian_phone, ucase(concat(stuFname, " ", stuMname, " ", stuSurname)) as student, st.sName as stream, st.class_id, indexNo as index')
                          ->join('students s', 'cr.student_id = s.stuID')
                          ->join('streams st', 'st.sid= s.stream_id')
                          ->where('cr.exam_id', $exam_id)
                          ->where('st.class_id', $class_id)
                          ->orderBy('s.indexNo', 'ASC')
                          ->get()->getResultArray();
        return $results;
    }

    public function getResultByStudent($student_id, $exam_id) {
        $results = $this->db->table('compiledresults cr')
                  ->select('cr.*, concat(stuFname, " ", stuSurname) as student, st.sName as stream, st.class_id')
                  ->join('students s', 'cr.student_id = s.stuID')
                  ->join('streams st', 'st.sid= s.stream_id')
                  ->where('cr.exam_id', $exam_id)
                  ->where('cr.student_id', $student_id)
                  ->get()->getRowArray();
        return $results;
    }

    public function examResults($exam_id) {
        $results = $this->db->table('compiledresults cr')
                          ->select('cr.*, exam_id as eid, stuID as sid, concat(stuFname, " ", stuSurname) as student, st.sName as stream, st.class_id')
                          ->join('students s', 'cr.student_id = s.stuID')
                          ->join('streams st', 'st.sid= s.stream_id')
                          ->where('cr.exam_id', $exam_id)
                          ->get()->getResultArray();
        return $results;
    }
    public function saveBatch($data){
        $sql = "INSERT INTO compiledresults(crID, exam_id, student_id, crResults, crCreated, crCompiledBy, raUpdated, raStatus, crTotal, crAverage, crPoints, crDivision) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        if($db->query($sql, [
                $data['exam'],
                $data['student'],
                $data['result'],
                $data['created'],
                $data['compiledby'],
                $data['updated'],
                $data['status'],
                $data['total'],
                $data['average'],
                $data['points'],
                $data['division']
            ])){
                return true;
            }else{
                return false;
            }
    }
    public function resultSummary($exam_id){
        $sql = "SELECT stuSex, crDivision, COUNT(crID) FROM compiledresults cr
                    JOIN students st ON cr.student_id = st.stuID
                    WHERE exam_id =? GROUP BY stuSex, crDivision
                    ORDER BY CASE crDivision
                            WHEN 'I' THEN 1 WHEN 'II' THEN 2
                            WHEN 'III' THEN 3  WHEN 'IV' THEN 4
                            WHEN '0' THEN 5  WHEN 'Inc' THEN 6
                            ELSE 7 END;";
        $query = $this->db->query($sql, [$exam_id])->getResultArray();
        return $query;
    }
}
