<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model{
    protected $table      = 'classes';
    protected $primaryKey = 'cid';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'named',
        'short',
        'numeral',
        'level',
        'created',
        'updated',
        'status',
    ];

    // Automatically handle created/updated timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created';
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

    public function nextClass($classID){
        $current = $this->find($classID);
        if (!$current) return null;
        return $this->where('level', $current['level'])
                    ->where('numeral', $current['numeral'] + 1)
                    ->where('status', 'Active')
                    ->first();
    }

}
