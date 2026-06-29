<?php

namespace App\Models;

use CodeIgniter\Model;

class PayrollRunModel extends Model
{
    protected $table = 'payroll_runs';
    protected $primaryKey = 'payroll_id';
    protected $allowedFields = [
        'payroll_month',
        'payroll_year',
        'status',
        'processed_by',
        'processed_at'
    ];
}
