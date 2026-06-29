<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectStreamModel extends Model{
    protected $table      = 'subject_streams';
    protected $primaryKey = 'scID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields = [
        'stream_id',
        'subject_id',
        'isMandatory',
        'hasPractical',
        'isSubsidiary',
        'subCreated',
        'subUpdated'
    ];

    // Automatically handle created/updated timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'subCreated';
    protected $updatedField  = 'subUpdated';

    public function add_subject($data=null){
        $sql = "INSERT INTO subject_streams(stream_id, subject_id, isMandatory, hasPractical, isSubsidiary, subCreated) VALUES (?, ?, ?, ?, ?, ?);";
        if($this->db->query($sql, [
            $data['stream'],
            $data['subject'],
            $data['mandatory'],
            $data['practical'],
            $data['subsidiary'],
            $data['created']
        ])){
            return true;
        }else{
            return false;
        }
    }
    // Get ONLY mandatory subjects for a stream
    public function getMandatorySubjects($stream_id) {
        return $this->select('subject_streams.*, subjects.subName')
            ->join('subjects', 'subjects.subID = subject_streams.subject_id')
            ->where('stream_id', $stream_id)
            ->where('isMandatory', 1)
            ->findAll();
    }
    public function getSubjects($stream_id) {
        return $this->select('
                                subject_streams.*, 
                                subjects.subName, subjects.subCode, subjects.subShort as short'
                            )
            ->join('subjects', 'subjects.subID = subject_streams.subject_id')
            ->where('stream_id', $stream_id)
            ->findAll();
    }
    
    public function get_details($stream=null){
        if ($stream !== null) {
            $builder = $this->db->table('subject_streams ss')
                        ->select('ss.*, cl.named as class, st.sName as stream, sb.subName as subject')
                        ->join('streams st', 'st.sid = ss.stream_id')
                        ->join('subjects sb', 'sb.subID = ss.subject_id')
                        ->join('classes cl', 'cl.cid = st.class_id')
                        ->where('ss.stream_id', $stream)
                        ->orderBy('ss.stream_id', 'ASC')
                        ->orderBy('sb.subName', 'ASC')
                        ->orderBy('cl.named', 'ASC');
            return $builder->get()->getResultArray();
        }else{
            $builder = $this->db->table('subject_streams ss')
                    ->select('ss.*, cl.named as class, st.sName as stream, subName as subject')
                    ->join('streams st', 'st.sid = ss.stream_id')
                    ->join('subjects sb', 'sb.subID = ss.subject_id')
                    ->join('classes cl', 'cl.cid = st.class_id')
                    ->orderBy('ss.stream_id', 'ASC')
                    ->orderBy('sb.subName', 'ASC')
                    ->orderBy('cl.named', 'ASC');
            return $builder->get()->getResultArray();
        }
    }
    public function get_Id($stream=null, $subject=null){
        if ($stream !== null) {
            $builder = $this->db->table('subject_streams ss')
                        ->select('ss.scID as id')
                        ->where('ss.stream_id', $stream)
                        ->where('ss.subject_id', $subject);
            return $builder->get()->getResultArray();
        }else{
            return null;
        }
    }

    //Get subject on specific classes
    public function getSubjectBelong($class = null){
        if ($class === null) {
            return $this->db->table('subject_streams ss')
                            ->select('distinct(subID) as id, subName as subject, subCode as code, subShort as abrev, class_id as class')
                            ->join('subjects sb', 'ss.subject_id=sb.subID')
                            ->join('streams st', 'st.sid = ss.stream_id')
                            ->orderBy('subName', 'ASC')
                            ->get()->getResultArray();
        }else{
            return $this->db->table('subject_streams ss')
                            ->select('distinct(subID) as id, subName as subject, subCode as code, subShort as abrev, class_id as class')
                            ->join('subjects sb', 'ss.subject_id=sb.subID')
                            ->join('streams st', 'st.sid = ss.stream_id')
                            ->where('st.class_id', $class)
                            ->orderBy('subName', 'ASC')
                            ->get()->getResultArray();
        }
            
    }


}
