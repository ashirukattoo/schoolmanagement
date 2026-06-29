<?php

namespace App\Models;

use CodeIgniter\Model;

class RegionModel extends Model{
    protected $table = 'regions';
    protected $primaryKey = 'regID';
    protected $allowedFields = [
        'regName', 'regZone', 'regLand'
    ];

    //Get route with specific status
    public function getOrdered($order =1){
        return $this->db->table('regions r')
                        ->select('r.*')
                        ->orderBy('r.regName', $order)
                        ->get()->getResultArray();
    }
}