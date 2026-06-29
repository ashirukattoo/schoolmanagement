<?php namespace App\Models;

use CodeIgniter\Model;

class TimetableModel extends Model{
    protected $table = 'timetable';
    protected $primaryKey = 'tmID';
    protected $allowedFields = [        
        'lesson',  'startat', 'endat',
        'periodNo',  'weekDay', 'term',
        'created_at',   'deleted_at',
        'status'
    ];

    public function del($id){
        if($this->delete($id)){
            return true;
        }else{
            return false;
        }
    }

    public function activeEmployee($status = 'active'){
        $builder = $this->db->table('employees e')
                    ->select('e.*')
                    ->where('e.empStatus', $status)
                    ->orderBy('e.empFname', 'ASC')
                    ->orderBy('e.empMname', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function activeTeacher($status = 'active'){
        $builder = $this->db->table('employees e')
                    ->select('e.*')
                    ->where('e.empStatus', $status)
                    ->where('e.empRole', 'Teacher')
                    ->orderBy('e.empFname', 'ASC')
                    ->orderBy('e.empMname', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getAll() {
        $builder = $this->db->table('employees e')
                    ->select('e.*')
                    ->orderBy('e.empFname', 'ASC')
                    ->orderBy('e.empMname', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getEmp($id) {
        $builder = $this->db->table('employees e')
                    ->select('e.*')
                    ->where('empID', $id);
        return $builder->get()->getRowArray();
    }
    
}