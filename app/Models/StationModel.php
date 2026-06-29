<?php

namespace App\Models;
use CodeIgniter\Model;

class StationModel extends Model{
    protected $table = 'stations';
    protected $primaryKey = 'staID';
    protected $allowedFields = [
        'staName','staGps','staBefore', 'street_id', 'staAfter','staCreated','staUpdated','staStatus'
    ];

    public function search($term){
        return $this->like('staName',$term)
                    ->orLike('staGps',$term)
                    ->findAll();
    }

    public function getAll(){
        $db = \Config\Database::connect();
        $builder = $db->table('stations sta')
                    ->select('sta.*, strName as street, waName as ward, disName as district, regName as region')
                    ->join('streets str', 'str.strID = sta.street_id', 'left')
                    ->join('wards wa', 'wa.waID = str.ward_id', 'left')
                    ->join('districts dis', 'dis.disID = wa.district_id', 'left')
                    ->join('regions r', 'r.regID = dis.region_id', 'left')
                    ->orderBy('r.regName', 'ASC')
                    ->orderBy('dis.disName', 'ASC');
        return $builder->get()->getResultArray();
    }
}