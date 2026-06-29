<?php namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model{
    protected $table = 'students';
    protected $primaryKey = 'stuID';
    protected $allowedFields = [
        'stuFname',
        'stuMname',
        'stuSurname',
        'stuSex',
        'stuDob',
        'stream_id',
        'guardian_id',
        'guardian_phone',
        'stuStatus',
        'indexNo'
    ];

    public function countExcept($data) {
        $total =$this->db->table('students')
                     ->whereNotIn('stuStatus', $data)
                     ->countAllResults();
        return $total;
    }
    public function count($data) {
        $total =$this->db->table('students')
                     ->orWhereIn('stuStatus', $data)
                     ->countAllResults();
        return $total;
    }    
    //Get student with their guardian and classes belongs
    public function getFullName($id = null){
        return $this->db->table('students st')
                ->select('concat(stuFname, " ", stuMname, " ", stuSurname) as name')
                ->where('st.stuID', $id)
                ->get()->getResultArray();         
    }
    //Get student with their guardian and classes belongs
    public function getFullDetails($status = 'active'){
        if ($status === 'active') {
            return $this->db->table('students st')
                            ->select('st.*, g.guName as guardian, g.guEmail as empEmail, cl.named as class, s.sName as stream, stuStatus as sts')
                            ->join('streams s', 's.sid = st.stream_id', 'left')
                            ->join('classes cl', 'cl.cid = s.class_id', 'left')
                            ->join('guardians g', 'g.guID = st.guardian_id', 'left')
                            ->where('st.stuStatus', 'active')
                            ->orderBy('cl.numeral', 'ASC')
                            ->orderBy('st.stuFname', 'ASC')
                            ->get()->getResultArray();
        }else{
            return $this->db->table('students st')
                            ->select('st.*, g.guName as guardian, g.guEmail as empEmail, cl.named as class, s.sName as stream, stuStatus as sts')
                            ->join('streams s', 's.sid = st.stream_id', 'left')
                            ->join('classes cl', 'cl.cid = s.class_id', 'left')
                            ->join('guardians g', 'g.guID = st.guardian_id', 'left')
                            ->orderBy('cl.numeral', 'ASC')
                            ->orderBy('st.stuFname', 'ASC')
                            ->get()->getResultArray();
        }         
    }
    //Get student with their guardian and Assigned route on specific class//Default Give Form One
    public function getFullDetailsByClass($class = 1){
        return $this->db->table('students st')
                ->select('st.*, g.empname as guardian, g.empEmail, r.rouName, cl.named as class')
                ->join('streams s', 's.sid = st.stream_id', 'left')
                ->join('classes cl', 'cl.cid = s.class_id', 'left')
                ->join('guardians g', 'g.guID = st.guardian_id', 'left')
                ->join('student_routestation sr', 'sr.student_id = st.stuID', 'left')
                ->join('route_stations rs', 'rs.rsID = sr.route_station_id', 'left')
                ->join('routes r', 'r.rouID = rs.route_id', 'left')
                ->where('s.class_id', $class)
                ->orderBy('cl.numeral', 'ASC')
                ->orderBy('st.stuFname', 'ASC')
                ->get()->getResultArray();
    }
    public function getByClass($class_id, $status='active'){
        return $this->db->table('students s')
                    ->select('stuID as id, ucase(concat(stuFname, " ", stuMname,  " ", stuSurname)) as name, stream_id as stream')
                    ->join('streams st', 'st.sid = s.stream_id')
                    ->where('st.class_id', $class_id)
                    ->where('stuStatus', $status)
                    ->orderBy('s.indexNo', 'ASC')
                    ->orderBy('stuSex', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->get()->getResultArray();
    }
    public function getByStream($stream_id, $status='active'){
        return $this->db->table('students s')
                        ->select('stuID as id, ucase(concat(stuFname, " ", stuMname,  " ", stuSurname)) as name, stream_id as stream')
                        ->join('streams st', 'st.sid = s.stream_id')
                        ->where('st.sid', $stream_id)
                        ->where('stuStatus', $status)
                        ->orderBy('s.indexNo', 'ASC')
                        ->orderBy('stuSex', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->get()->getResultArray();
    }
    public function getByStatus($class_id=null, $status='active'){
        if ($class_id===null) {
         return $this->db->table('students s')
                        ->select('stuID as id, stuFname as fname, stuMname as mname, stuSurname as surname, stuSex as sex,  concat(named, "-", sName) as class')
                        ->join('streams st', 'st.sid = s.stream_id')
                        ->join('classes cl', 'st.class_id = cl.cid')
                        ->where('stuStatus', $status)                        
                        ->orderBy('cl.numeral', 'ASC')
                        ->orderBy('stream_id', 'ASC')
                        ->orderBy('sName', 'ASC')
                        ->orderBy('stuSex', 'ASC')
                        ->orderBy('stuFname', 'ASC')
                        ->orderBy('stuMname', 'ASC')
                        ->orderBy('stuSurname', 'ASC')
                        ->get()->getResultArray();
        }else{
         return $this->db->table('students s')
                         ->select('stuID as id, stuFname as fname, stuMname as mname, stuSurname as surname, stuSex as sex,  concat(named, "-", sName) as class')
                        ->join('streams st', 'st.sid = s.stream_id')
                        ->join('classes cl', 'st.class_id = cl.cid')
                        ->where('st.class_id', $class_id)
                        ->where('stuStatus', $status)
                        ->orderBy('stream_id', 'ASC')
                        ->orderBy('cl.numeral', 'ASC')
                        ->orderBy('sName', 'ASC')
                        ->orderBy('stuSex', 'ASC')
                        ->orderBy('stuFname', 'ASC')
                        ->orderBy('stuMname', 'ASC')
                        ->orderBy('stuSurname', 'ASC')
                        ->get()->getResultArray();   
        }
        
    }
    public function del($id){
        if($this->delete($id)){
            return true;
        }else{
            return false;
        }
    }
    public function updateStream($studentID, $streamID){
        return $this->update($studentID, [
            'stream_id' => $streamID,
            'stuStatus' => 'Active'
        ]);
    }

    //Search student by name
    public function search($keyword){
        return $this->db->table('students')
                        ->like('stuFname', $keyword)
                        ->orLike('stuMname', $keyword)
                        ->orLike('stuSurname', $keyword)
                        ->get()->getResultArray();
    }
    //Search student by id
    public function getRecord($id=null){
        return $this->db->table('students')
                        ->where('stuID', $id)
                        ->get()->getResultArray();
    }
    //Record Students Details in Database
    public function insertStudent($data){
        try {
            $sql = "CALL insertStudent(?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, [
                $data['stuFname'],
                $data['stuMname'],
                $data['stuSurname'],
                $data['stuSex'],
                $data['stuDob'],
                $data['stream_id'],
                $data['guardian_id']
            ]);
            return true;
        } catch (Exception $e) {
            return ['error' =>$e->getMessage()];
        }
    }
    //Get All students with 
    public function getWithStudentRoute()    {
        return $this->db->table('students st')
                    ->select('st.*, g.empname as guardian, g.empEmail, r.rouName, cl.named as class, sr.status')
                    ->join('streams s', 's.sid = st.stream_id', 'left')
                    ->join('classes cl', 's.class_id = cl.cid', 'left')
                    ->join('guardians g', 'g.guID = st.guardian_id', 'left')
                    ->join('student_routestation sr', 'sr.student_id = st.stuID', 'left')
                    ->join('route_stations rs', 'rs.rsID = sr.route_station_id', 'left')
                    ->join('routes r', 'r.rouID = rs.route_id', 'left')
                    ->orderBy('cl.numeral', 'ASC')
                    ->orderBy('st.stuFname', 'ASC')
                    ->get()->getResultArray();
    }
    public function getNextStream($level=null, $class_id=null)   {
        //sql
        $sql = "SELECT stuID, concat(ucase(stuFname), ' ', ucase(stuMname), ' ', ucase(stuSurname)) as fullname, s.sid AS current_stream_id, concat(c.named, '-', s.sName) AS current_stream, ns.sid AS next_stream_id, concat(nc.named, '-', ns.sName) AS next_stream FROM students st
        JOIN streams s ON st.stream_id = s.sid
        JOIN classes c ON s.class_id = c.cid
        JOIN classes nc ON nc.numeral = c.numeral + 1
        JOIN streams ns ON ns.class_id = nc.cid  AND ns.sName = s.sName
        WHERE c.cid = 1;"; 
    }
}