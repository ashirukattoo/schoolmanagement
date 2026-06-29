<?php

namespace App\Models;

use CodeIgniter\Model;

class SalaryGradeModel extends Model{
    protected $table = 'salary_grades';
    protected $primaryKey = 'grade_id';
    protected $allowedFields = [
        'grade_name',
        'basic_salary',
        'housing_allowance',
        'transport_allowance',
        'medical_allowance',
        'other_allowance'
    ];
}
