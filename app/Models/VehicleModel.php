<?php

namespace App\Models;
use CodeIgniter\Model;

class VehicleModel extends Model
{
    protected $table = 'vehicles';
    protected $primaryKey = 'veID';
    protected $allowedFields = [
        'vePlateNumber','veModel','veOwnership','veNamed'
    ];

    // Search vehicle by plate or model
    public function search($term)
    {
        return $this->like('vePlateNumber',$term)
                    ->orLike('veModel',$term)
                    ->findAll();
    }

    public function getAll() {
        return $this->select('vehicles.*')
                    ->findAll();
    }
}