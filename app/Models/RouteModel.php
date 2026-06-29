<?php namespace App\Models;

use CodeIgniter\Model;

class RouteModel extends Model{
    protected $table = 'routes';
    protected $primaryKey = 'rouID';
    protected $allowedFields = [
        'rouName',   'rouStart', 'rouEnd',
        'rouCreated',   'rouUpdated',  'rouStatus'
    ];
    //Record Route Details in Database
    public function insertRoute($data){
        try {
            $sql = "CALL insertRoute(?, ?, ?)";
            $this->db->query($sql, [
                $data['route'],
                $data['start'],
                $data['end']
            ]);
            return true;
        } catch (Exception $e) {
            return ['error' =>$e->getMessage()];
        }
    }

    public function erase($route = 0)  {
        $this->db->delete($route);
    }

    //Get route with Stations
    public function getWithStations(){
        return $this->db->table('routes r')
                        ->select('r.*, s.staName, s.staName, s.staGps, rs.rsStatus')
                        ->join('route_stations rs', 'rs.route_id = r.rouID', 'left')
                        ->join('stations s', 's.staID = rs.station_id', 'left')
                        ->get()->getResultArray();
    }
    //Get Specific route
    public function getIt($id){
        return $this->db->table('routes r')
                    ->select('rouID as id, rouName as name, rouStart as start, rouEnd as end, rouStatus as status')
                    ->where('r.rouID', $id)
                    ->get()->getRowArray();
    }
    //Get route with specific status
    public function getWithStatus($status = 'active'){
        return $this->db->table('routes r')
                        ->select('r.*')
                        ->where('r.rouStatus', $status)
                        ->get()->getResultArray();
    }
    //Search Route by name
    public function search($keyword){
        return $this->db->table('routes')
                        ->get()->getResultArray();
    }
}