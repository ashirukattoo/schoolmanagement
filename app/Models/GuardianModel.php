<?php namespace App\Models;

use CodeIgniter\Model;

class GuardianModel extends Model{
    protected $table = 'guardians';
    protected $primaryKey = 'guID';
    protected $allowedFields = [ 
        'empname',
        'guSex',
        'empDob', 
        'guOccupasion',
        'empEmail',
        'empPassword',
        'guRegisterd',
        'guStatus'
    ];
}