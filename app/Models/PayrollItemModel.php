<?php

namespace App\Models;

use CodeIgniter\Model;

class PayrollItemModel extends Model
{
    protected $table      = 'payroll_items';
    protected $primaryKey = 'item_id';

    protected $allowedFields = [
        'payroll_id',
        'employee_id',
        'basic_salary',
        'housing_allowance',
        'transport_allowance',
        'medical_allowance',
        'other_allowance',
        'gross_salary',
        'total_deductions',
        'net_salary'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getItems($payRol){
        $builder = $this->db->table('payroll_items pi')
                    ->select('pi.*, ucase(concat(empFname, " ", empMname, " ", empSurname)) as employee')
                    ->join('employees e', 'pi.employee_id = e.empID', 'INNER')
                    ->where('pi.payroll_id', $payRol)
                    ->orderBy('e.empFname', 'ASC')
                    ->orderBy('e.empMname', 'ASC');
        return $builder->get()->getResultArray();
    }
}
