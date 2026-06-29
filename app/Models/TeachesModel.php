<?php namespace App\Models;

use CodeIgniter\Model;

class TeachesModel extends Model{
    protected $table = 'employee_teaches';
    protected $primaryKey = 'code';
    protected $allowedFields = [        
        'employee_id',  'stream_id', 'assignDate',
        'todate',  'created', 'status'
    ];

    public function activeEmployee($status = 'active'){
        $builder = $this->db->table('employees e')
                    ->select('e.*')
                    ->where('e.empStatus', $status)
                    ->orderBy('e.empFname', 'ASC')
                    ->orderBy('e.empMname', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function addTeacher($data=null) { 
        if ($data === null) {
            $sql = $this->db->table('employee_teaches et')
                  ->select("code as id, concat(named, '-', sName) as class, subName as subject, concat(empFname, ' ', empMname, ' ', empSurname) as teacher, assigneDate, todate as until, et.status")
                  ->join('employees e', 'e.empID = et.employee_id')
                  ->join('subject_streams ss', 'ss.scID = et.stream_id')
                  ->join('subjects sb', 'sb.subID = ss.subject_id')
                  ->join('streams st', 'st.sid = ss.stream_id')
                  ->join('classes cl', 'cl.cid= st.class_id')
                  ->where('et.status', 'Active');
            $query = $sql->get()->getResultArray();
            return $query;
        }else{
            $sql = "INSERT INTO employee_teaches(code, employee_id, stream_id, assigneDate, todate, created, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, [
                'AC'.$data['code'],
                $data['employee_id'],
                $data['stream_id'],
                $data['assigneDate'],
                $data['todate'],
                date('Y-m-d H:i:s'),
                'Active'
            ])){
                return true;
            }else{
                return false;
            }
       }
    }
}