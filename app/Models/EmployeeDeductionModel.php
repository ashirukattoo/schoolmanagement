<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeDeductionModel extends Model
{
    protected $table      = 'employee_deductions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'empID',
        'deductionID',
        'custom_value',
        'is_active',
        'created_at'
    ];
}
