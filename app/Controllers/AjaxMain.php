<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DistrictModel;
use App\Models\WardModel;
use App\Models\StreetModel;
class AjaxMain extends BaseController
{
    public function getDistricts($region_id) {
        $model = new DistrictModel();
        $data = $model->where('region_id', $region_id)->orderBy('disName', 'ASC')->findAll();

        return $this->response->setJSON($data);
    }

    public function getWards($district_id)  {
        $model = new WardModel();
        $data = $model->where('district_id', $district_id)->findAll();

        return $this->response->setJSON($data);
    }

    public function getStreets($ward_id)  {
        $model = new StreetModel();
        $data = $model->where('ward_id', $ward_id)->findAll();

        return $this->response->setJSON($data);
    }

    public function fetchUnassignedStudents(){
        $class_id = $this->request->getPost('class_id');
        $route_id = $this->request->getPost('route_id');
        $tour_id  = $this->request->getPost('tour_id');

        // get tour-route record
        $tourRoute = $this->tourRouteModel
            ->where('tour_id', $tour_id)
            ->where('route_id', $route_id)
            ->first();

        if (!$tourRoute) {
            return $this->response->setJSON(['students'=>[], 'stations'=>[], 'error'=>'No route for this tour']);
        }

        // Get route stations
        $stations = $this->routeStationModel
            ->select('route_stations.rsID, stations.staName')
            ->join('stations', 'stations.staID = route_stations.station_id')
            ->where('route_stations.route_id', $route_id)
            ->findAll();

        // Get students not assigned to this tour
        $students = $this->studentModel
            ->select("students.*, CONCAT(stuFname,' ',COALESCE(stuMname,''),' ',stuSurname) AS name")
            ->join('streams','streams.sid = students.stream_id')
            ->where('streams.class_id', $class_id)
            ->whereNotIn(
                'students.stuID',
                function($builder) use ($tour_id){
                    return $builder->select('student_id')
                        ->from('student_routestation sr')
                        ->join('route_stations rs', 'rs.rsID = sr.route_station_id')
                        ->join('tour_routes tr', 'tr.route_id = rs.route_id')
                        ->where('sr.status','active')
                        ->where('tr.tour_id',$tour_id);
                }
            )->findAll();

        return $this->response->setJSON([
            'students' => $students,
            'stations' => $stations
        ]);
    }
    public function getTerms($ay_id)  {
        $db = \Config\Database::connect();
        $data = $db->table('terms')->where('ay_id', $ay_id)->get()->getRowArray();
        return $this->response->setJSON($data);
    }
}
