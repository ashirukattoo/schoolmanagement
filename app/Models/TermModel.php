<?php namespace App\Models;

use CodeIgniter\Model;

class TermModel extends Model
{
    protected $table = 'terms';
    protected $primaryKey = 'tID';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tID', 'tName', 'ay_id',
        'tStart', 'tEnd',
        'tCategory', 'tStatus',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function getByAcademicYear($ayID){
        return $this->where('ay_id', $ayID)
                    ->orderBy('tStart', 'ASC')
                    ->findAll();
    }

    public function saveTerm($data){
        $insert = $this->insert($data);
         return $insert;
    }

    public function getTermId(){
        $db = \Config\Database::connect();
        $builder = $db->table('terms')->selectCount('tID', 'idadi');
        $query = $builder->get()->getRowArray();

        if (!$query) return false;
        $num = $query['idadi'];
        if ($num < 9) {
            $num = '00000' . ($num + 1);
        } elseif ($num < 99) {
            $num = '0000' . ($num + 1);
        } elseif ($num < 999) {
            $num = '000' . ($num + 1);
        } elseif ($num < 9999) {
            $num = '00' . ($num + 1);
        } elseif ($num < 99999) {
            $num = '0' . ($num + 1);
        } else {
            $num = ($num + 1);
        }
        $id = 'ACT' . $num;
        return $id;
    }

    public function setCurrent($tID)
    {
        // Reset all
        $this->where('tStatus', 'Current')
             ->set(['tStatus' => 'Previous'])
             ->update();

        // Set selected
        return $this->update($tID, ['tStatus' => 'Current']);
    }

    public function current()
    {
        return $this->where('tStatus', 'Current')->first();
    }
}
