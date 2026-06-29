<?php

namespace App\Models;
use CodeIgniter\Model;

class TourRouteModel extends Model
{
    protected $table = 'tour_routes';
    protected $primaryKey = 'trID';
    protected $allowedFields = ['tour_id','route_id','employee_id','vehicle_id','trDeparture','trArrival','trPosition','trStatus'];

    public function getFullAssignments() {
        return $this->select('tour_routes.*, tours.touName, routes.rouName, employees.empFname as first_name, employees.empSurname as surname, vehicles.vePlateNumber')
                    ->join('tours','tours.touID=tour_routes.tour_id')
                    ->join('routes','routes.rouID=tour_routes.route_id')
                    ->join('employees','employees.empID=tour_routes.employee_id')
                    ->join('vehicles','vehicles.veID=tour_routes.vehicle_id')
                    ->findAll();
    }

    public function add($data=null){
        $sql = "INSERT INTO tour_routes(tour_id, route_id, employee_id, vehicle_id) VALUES (?, ?, ?, ?);";
        if ($this->db->query($sql, [
            $data['tour'],
            $data['route'],
            $data['employee'],
            $data['vehicle']
        ])) {
            return true;
        }else{
            return false;
        }
    }
}