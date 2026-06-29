<?php

namespace App\Models;

use CodeIgniter\Model;

class DeductionModel extends Model
{
    protected $table = 'deductions';
    protected $primaryKey = 'deductionID';

    protected $allowedFields = [
        'deduction_name',
        'deduction_type',     // percentage | fixed
        'deduction_value',
        'applies_to',         // basic | gross
        'is_active'
    ];
}
