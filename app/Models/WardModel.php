<?php

namespace App\Models;

use CodeIgniter\Model;

class WardModel extends Model
{
    protected $table = 'wards';
    protected $primaryKey = 'waID';
    protected $allowedFields = [
        'waName', 'district_id'
    ];

    public function getAll(){
        $builder = $this->db->table('wards wa')
                    ->select('wa.*, r.disName as district, r.disId as did')
                    ->join('districts r', 'r.disID = wa.district_id', 'left')
                    ->orderBy('r.disName', 'ASC')
                    ->orderBy('waName', 'ASC');
        return $builder->get()->getResultArray();
    }
}