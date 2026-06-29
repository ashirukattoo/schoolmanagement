<?php namespace App\Models;

use CodeIgniter\Model;

class SchoolSettingModel extends Model{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'school_name',
        'school_motto',
        'school_logo',
        'school_email',
        'school_phone',
        'school_address',
        'current_ay',
        'current_term',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    /**
     * Get main school context (always row id = 1)
     */
    public function context()
    {
        return $this->find(1);
    }
}
