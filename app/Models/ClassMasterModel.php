<?php namespace App\Models;

use CodeIgniter\Model;

class ClassMasterModel extends Model{
    protected $table = 'class_masters';
    protected $primaryKey = 'cmID';
    protected $allowedFields = [        
        'employee_id',  'stream_id', 'cmAssignDate',
        'cmTodate',  'cmCreated', 'cmStatus'
    ];


    public function updateStatus($id, $value){
        return $this->update($id, ['cmStatus'=> $value, 'cmTodate' => date('Y-m-d')]);
    }

    public function addTeacher($data=null, $status='Active') {
        if ($data === null) {
            $sql = $this->db->table('class_masters cm')
                  ->select("cmID as id, concat(empFname, ' ', empSurname) as teacher, concat(named, ' - ', Sname) as class, cmAssigneDate as since, cmTodate as until, status")
                  ->join('employees e', 'e.empID = cm.employee_id')
                  ->join('streams st', 'st.sid = cm.stream_id')
                  ->join('classes cl', 'cl.cid= st.class_id')
                  ->where('status', $status)
                  ->orderBy('named', 'ASC')
                  ->orderBy('st.sName', 'ASC')
                  ->orderBy('cmTodate', 'ASC');
            $query = $sql->get()->getResultArray();
            return $query;
        }else{
            $sql = "INSERT INTO class_masters(cmID, employee_id, stream_id, cmAssigneDate, cmTodate, cmCreated, cmStatus) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if($this->db->query($sql, [
                'CM'.$data['id'],
                $data['employee'],
                $data['stream'],
                $data['assigndate'],
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