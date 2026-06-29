<?php namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model{
    protected $table = 'employees';
    protected $primaryKey = 'empID';
    protected $allowedFields = [        
        'empFname',  'empMname', 'empSurname',
        'empSex',  'empDob', 'empPhone', 'empPosition',
        'empEmail',   'empPassword',
        'empHired', 'empRegisterd', 'empStatus', 'empSalaryGradeID'
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
                    ->select('e.*, sg.grade_name as grade')
                    ->join('salary_grades sg', 'e.empSalaryGradeID = sg.grade_id', 'LEFT')
                    ->where('e.empStatus', $status)
                    ->orderBy('e.empFname', 'ASC')
                    ->orderBy('e.empMname', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function activeTeacher($status = 'active'){
        $builder = $this->db->table('employees e')
                    ->select('e.*')
                    ->join('employee_roles er', 'er.employee_id = e.empID')
                    ->where('e.empStatus', $status)
                    ->whereNotIn('er.erRole', ['Head', 'Manager', 'Director', 'Human Resource', 'Accoutant', 'Specific Task', 'Admin', 'Chief Cooker', 'Security Guard'])
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

    public function getRoles($id){
        $builder = $this->db->table('employee_roles er')
                        ->select('er.*')
                        ->where('employee_id', $id);
        return $builder->get()->getResultArray();
    }
    
}