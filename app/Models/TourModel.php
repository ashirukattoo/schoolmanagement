<?php

namespace App\Models;

use CodeIgniter\Model;

class TourModel extends Model{
    protected $table = 'tours';
    protected $primaryKey = 'touID';
    protected $allowedFields = [
        'touName', 'touCategory', 'touStart', 'touEnd', 'touStatus'
    ];

    public function add($data =null){
        $sql = "INSERT INTO tours (touName, touCategory, touStart, touEnd, touStatus) VAlUES (?, ?, ?, ?, ?)";
        if($this->db->query($sql, [
            $data['tour'],
            $data['category'],
            $data['start'],
            $data['end'],
            $data['status']
        ])){
            return true;
        }else{
            return false;
        }
    }
}