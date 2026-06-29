<?php

namespace App\Models;

use CodeIgniter\Model;

class StreetModel extends Model
{
    protected $table = 'streets';
    protected $primaryKey = 'strID';
    protected $allowedFields = [
        'strName', 'ward_id'
    ];

    public function getAll(){
        $db = \Config\Database::connect();
        $builder = $db->table('streets str')
                    ->select('str.*, r.waName as ward, r.waID as wid')
                    ->join('wards r', 'r.waID = str.ward_id', 'left')
                    ->orderBy('r.waName', 'ASC')
                    ->orderBy('str.strName', 'ASC');
        return $builder->get()->getResultArray();
    }
}