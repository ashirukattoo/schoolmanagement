<?php namespace App\Models;

use CodeIgniter\Model;

class EmployeeRoles extends Model{
    protected $table = 'employee_roles';
    protected $primaryKey = 'erID';
    protected $allowedFields = ['employee_id',       
        'erRole',  'erAssigned', 'erUnAssigned',
        'erCreated',  'erUpdated', 'erStatus'
    ];

    public function add($data){
        $sql = "INSERT INTO employee_roles (employee_id, erRole, erAssigned, erCreated) VAlUES (?, ?, ?, ? )";
        if($this->db->query($sql, [
            $data['id'],
            $data['role'],
            $data['assigned'],
            date('Y-m-d H:i:s')
        ])){
            return true;
        }else{
            return false;
        }
    }

    public function updateStatus($id, $value){
        return $this->update($id, ['erStatus'=> $value]);
    }

    public function del($id){
        if($this->delete($id)){
            return true;
        }else{
            return false;
        }
    }

    public function getMyRoles($employee, $status = 'Active'){
        if ($status === 'all') {
            $builder = $this->db->table('employee_roles')
                    ->select('erRole as role, erStatus as status')
                    ->where('employee_id', $employee)
                    ->orderBy('erRole', 'ASC')
                    ->orderBy('erAssigned', 'DESC');
        }else{
            $builder = $this->db->table('employee_roles')
                    ->select('erRole as role, erStatus as status')
                    ->where('erStatus', $status)
                    ->where('employee_id', $employee)
                    ->orderBy('erRole', 'ASC')
                    ->orderBy('erAssigned', 'DESC');
        }        
        return $builder->get()->getResultArray();
    }

    public function isAdmin($employee){
        $sql = "SELECT roleName FROM employee_roles as er JOIN roles as r on er.erRole=r.id where employee_id =? AND erStatus = 'Active' AND r.roleName = 'Admin';";
        $query = $this->db->query($sql, [$employee])->getRow();
        return $query ? true : false;
    }
    public function isAcademic($employee){
        $sql = "SELECT roleName FROM employee_roles as er JOIN roles as r on er.erRole=r.id where employee_id =? AND erStatus = 'Active' AND r.roleName = 'Academic';";
        $query = $this->db->query($sql, [$employee])->getRow();
        return $query ? true : false;
    }  
    public function isHead($employee){
        $sql = "SELECT roleName FROM employee_roles as er JOIN roles as r on er.erRole=r.id where employee_id =? AND erStatus = 'Active' AND r.roleName = 'Head';";
        $query = $this->db->query($sql, [$employee])->getRow();
        return $query ? true : false;
    }
    public function isDeputy($employee){
        $sql = "SELECT roleName FROM employee_roles as er JOIN roles as r on er.erRole=r.id where employee_id =? AND erStatus = 'Active' AND r.roleName = 'Deputy';";
        $query = $this->db->query($sql, [$employee])->getRow();
        return $query ? true : false;
    }
    public function isManager($employee){
        $sql = "SELECT roleName FROM employee_roles as er JOIN roles as r on er.erRole=r.id where employee_id =? AND erStatus = 'Active' AND r.roleName = 'Manager';";
        $query = $this->db->query($sql, [$employee])->getRow();
        return $query ? true : false;
    }
    public function isCM($employee){
        $sql = "SELECT roleName FROM employee_roles as er JOIN roles as r on er.erRole=r.id where employee_id =? AND erStatus = 'Active' AND r.roleName = 'Class Master';";
        $query = $this->db->query($sql, [$employee])->getRow();
        return $query ? true : false;
    }
    public function isAccountant($employee){
        $sql = "SELECT roleName FROM employee_roles as er JOIN roles as r on er.erRole=r.id where employee_id =? AND erStatus = 'Active' AND r.roleName = 'Accountant';";
        $query = $this->db->query($sql, [$employee])->getRow();
        return $query ? true : false;
    }
}