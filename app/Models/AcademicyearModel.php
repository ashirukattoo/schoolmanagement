<?php namespace App\Models;

use CodeIgniter\Model;

class AcademicyearModel extends Model
{
    protected $table      = 'academicyears';
    protected $primaryKey = 'ayID';
    protected $allowedFields = ['ayName', 'ayLevel', 'ayStart', 'ayEnd',
        'ayCreated', 'ayUpdated', 'ayStatus'
    ];

    /**
     * Save academic year with custom ID generation
     */
    public function saveAy($data){
        $db = \Config\Database::connect();

        // Count rows
        $builder = $db->table('academicyears')->selectCount('ayID', 'idadi');
        $query = $builder->get()->getRowArray();

        if (!$query) return false;

        $num = $query['idadi'];

        if ($num < 10) {
            $num = '000' . ($num + 1);
        } elseif ($num < 100) {
            $num = '00' . ($num + 1);
        } elseif ($num < 1000) {
            $num = '0' . ($num + 1);
        } else {
            $num = ($num + 1);
        }

        $id = 'ACOL' . $num;

        // INSERT using correct keys
        $sql = "INSERT INTO academicyears(ayID, ayName, ayLevel, ayStart, ayEnd, ayCreated, ayUpdated, ayStatus)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        return $db->query($sql, [
            $id,
            $data['ayName'],
            $data['level'],
            $data['start_date'],
            $data['end_date'],
            date('Y-m-d H:i:s'),
            null,
            'next'
        ]);
    }
    public function getAll()    {
        return $this->orderBy('ayStart', 'DESC')->findAll();
    }

    public function setCurrent($ayID) {
        // Reset all
        $this->where('ayStatus', 'Current')
             ->set(['ayStatus' => 'Previous'])
             ->update();
        // Set selected
        return $this->update($ayID, ['ayStatus' => 'Current']);
    }

    public function current(){
        return $this->where('ayStatus', 'Current')->first();
    }

}
