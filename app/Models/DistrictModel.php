<?php

namespace App\Models;

use CodeIgniter\Model;

class DistrictModel extends Model
{
    protected $table = 'districts';
    protected $primaryKey = 'disID';
    protected $allowedFields = [
        'disName', 'region_id'
    ];

    public function getAll(){
        $db = \Config\Database::connect();
        $builder = $db->table('districts ds')
                    ->select('ds.*, r.regName as region, r.regId as rid')
                    ->join('regions r', 'r.regID = ds.region_id', 'left');
        return $builder->get()->getResultArray();
    }
}