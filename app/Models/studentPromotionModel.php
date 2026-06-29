<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentPromotionModel extends Model{
    protected $table      = 'student_promotions';
    protected $primaryKey = 'spid';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'student_id',
        'stream_id',
        'class_id',
        'level',
        'action',
        'action_date',
        'updated',
        'created_at',
    ];

    // Automatically handle created/updated timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated';

    // Optional: default order
    public function getAllClasses($status = 'active'){
        return $this->where('status', $status)
                    ->orderBy('level', 'ASC')
                    ->orderBy('numeral', 'ASC')
                    ->findAll();
    }

    public function getAll(){
        return $this->orderBy('level', 'ASC')
                    ->orderBy('numeral', 'ASC')
                    ->findAll();
    }

    public function getLevel($id){
        $sql = "SELECT level FROM classes WHERE cid = ?";
        $query = $this->db->query($sql, [$id])->getRow();

        return $query ? $query->level : null;   // direct value
    }
}
