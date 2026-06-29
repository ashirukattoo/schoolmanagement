<?php

namespace App\Models;

use CodeIgniter\Model;

class PayrollItemDeductionModel extends Model
{
    protected $table      = 'payroll_item_deductions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['payroll_item_id', 'deduction_name', 'amount', 'created_at'];

    // Optional: automatically handle created_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
