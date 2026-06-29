<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model{
    protected $table      = 'subjects';
    protected $primaryKey = 'subID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields = [
        'subName',
        'subCode', 'subShort',
        'subCategory',
        'subLevel',
        'subCurriculum',
        'subCreated',
        'subUpdated'
    ];

    // Automatically handle created/updated timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'subCreated';
    protected $updatedField  = 'subUpdated';

    public function add_subject($data=null){
        $sql = "INSERT INTO subjects(subCode, subShort, subName, subCategory, subCurriculum, subLevel, subCreated) VALUES(?, ?, ?, ?, ?, ?, ?)";
        if($this->db->query($sql, [
            $data['code'],
            $data['short'],
            $data['name'],
            $data['category'],
            $data['curriculum'],
            $data['level'],
            $data['created']
        ])){
            return true;
        }else{
            return false;
        }
    }

    public function myId($name=null){
        $sql = "SELECT * FROM subjects where subName ='".$name."'  OR subShort ='".$name."';";
        $query=$this->db->query($sql);
        return $query->getResultArray();
    }

    //delete totaly the subject
    public function del($id){
        if($this->delete($id)){
            return true;
        }else{
            return false;
        }
    }
    public function getName($code){
        $sql = "SELECT subName 
                FROM subjects
                WHERE subShort = ? LIMIT 1";

        $query = $this->db->query($sql, [$code])->getRow();

        return $query ? $query->subName : $code;   // direct value
    }
}
