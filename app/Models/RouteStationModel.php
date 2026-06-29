<?php

namespace App\Models;

use CodeIgniter\Model;

class RouteStationModel extends Model
{
    protected $table            = 'route_stations';
    protected $primaryKey       = 'rsID';
    protected $allowedFields    = ['route_id', 'station_id', 'rsCreated', 'rsStatus'];

    protected $useTimestamps    = false;

    /**
     * Get all route–station combinations with joined route and station details
     */
    public function getAllRouteStations() {
        return $this->select('route_stations.rsID, routes.rouName, stations.staName, route_stations.rsStatus, route_stations.rsCreated')
                    ->join('routes', 'routes.rouID = route_stations.route_id')
                    ->join('stations', 'stations.staID = route_stations.station_id')
                    ->orderBy('rouName ASC, staName ASC')
                    ->findAll();
    }

    /**
     * Get all route–station on specific route
     */
    public function getRouteStations($route_id) {
        return $this->select('rsID as id, staName as station, strName as street, waName as ward, disName as district, regName as region, rsStatus as status')
                    ->join('stations', 'stations.staID = route_stations.station_id', 'left')
                    ->join('streets st', 'st.strID = stations.street_id', 'left')
                    ->join('wards w', 'w.waID = st.ward_id')
                    ->join('districts d', 'd.disID = w.district_id', 'left')
                    ->join('regions r', 'r.regID = d.region_id')
                    ->where('route_stations.route_id', $route_id)
                    ->orderBy('staName ASC')
                    ->findAll();
    }

    /**
     * Get route-stations for a specific route
     */
    public function getByRoute($route_id) {
        return $this->select('route_stations.*, routes.rouName, stations.staName')
                    ->join('routes', 'routes.rouID = route_stations.route_id')
                    ->join('stations', 'stations.staID = route_stations.station_id')
                    ->where('route_stations.route_id', $route_id)
                    ->findAll();
    }

    /**
        Get single route–station record
    * */ 
    public function getRouteStation($rsID) {
        return $this->select('route_stations.*, routes.rouName, stations.staName')
                    ->join('routes', 'routes.rouID = route_stations.route_id')
                    ->join('stations', 'stations.staID = route_stations.station_id')
                    ->where('route_stations.rsID', $rsID)
                    ->first();
    }

    public function add($data=null){
        $sql = "INSERT INTO route_stations(route_id, station_id, rsCreated, rsStatus) VALUES (?, ?, ?, ?);";
        if($this->db->query($sql, [
            $data['route'],
            $data['station'],
            date('Y-m-d H:i:s'),
            $data['status']
        ])){
            return true;
        }else{
            return false;
        }
    }

}
