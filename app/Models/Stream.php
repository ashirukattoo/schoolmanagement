<?php

namespace App\Models;

use CodeIgniter\Model;

class Stream extends Model{
    protected $table      = 'streams';
    protected $primaryKey = 'sid';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'class_id',
        'sName',
        'sCreated',
        'sUpdated',
        'sStatus'
    ];

    // Automatically handle created/updated timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'sCreated';
    protected $updatedField  = 'sUpdated';


    // Get Streams with Classe
    public function getAllClasses(){
        return $this->db->table('streams st')
                    ->select('st.*, cl.named as class ')
                    ->join('classes cl', 'st.class_id = cl.cid')
                    ->orderBy('cl.numeral', 'ASC')
                    ->orderBy('sName', 'ASC')
                    ->get()->getResultArray();
    }

    public function getMe($id=''){
        $sql = "SELECT sName from streams where sid = (select stream_id from students where stuID =?)";

        $query = $this->db->query($sql, [$id])->getRow();

        return $query ? $query->sName : null;   // direct value
    }

    //Record Stream Details in Database
    public function insertStream($data){
        try {
            $sql = "CALL insertStream(?, ?, ?)";
            $this->db->query($sql, [
                $data['name'],
                $data['class'],
                $data['status']
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    //delete totaly the stream
    public function del($id){
        if($this->delete($id)){
            return true;
        }else{
            return false;
        }
    }

    public function updateStatus($id, $value){
        return $this->update($id, ['sStatus'=> $value]);
    }

    public function findSameStream($nextClassID, $streamName){
        return $this->where('class_id', $nextClassID)
                    ->where('sName', $streamName)
                    ->where('sStatus', 'Active')
                    ->first();
    }

    public function firstStream($classID){
        return $this->where('class_id', $classID)
                    ->where('sStatus', 'Active')
                    ->orderBy('sid','ASC')
                    ->first();
    }

}
