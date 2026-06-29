<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentRouteStationModel extends Model
{
    protected $table = 'student_routestation';
    protected $primaryKey = 'srsID';
    protected $allowedFields = [
        'student_id', 'route_station_id', 'assigned_by', 'assigned_at', 'updated_at', 'status'
    ];

    // Join to get readable info
    public function getAllAssignments($status =1){
        if ($status === 1) {
           return $this->select('student_routestation.*, students.stuFname, students.stuMname, students.stuSurname, stations.staName, routes.rouName, employees.empFname, employees.empSurname')
                    ->join('route_stations', 'route_stations.rsID = student_routestation.route_station_id')
                    ->join('routes', 'routes.rouID = route_stations.route_id')
                    ->join('stations', 'stations.staID = route_stations.station_id')
                    ->join('students', 'students.stuID = student_routestation.student_id')
                    ->join('employees', 'employees.empID = student_routestation.assigned_by', 'left')
                    ->orderBy('student_routestation.assigned_at', 'DESC')
                    ->findAll();
        }else{
            return $this->select('student_routestation.*, students.stuFname, students.stuMname, students.stuSurname, stations.staName, routes.rouName, employees.empFname, employees.empSurname')
                    ->join('route_stations', 'route_stations.rsID = student_routestation.route_station_id')
                    ->join('routes', 'routes.rouID = route_stations.route_id')
                    ->join('stations', 'stations.staID = route_stations.station_id')
                    ->join('students', 'students.stuID = student_routestation.student_id')
                    ->join('employees', 'employees.empID = student_routestation.assigned_by', 'left')
                    ->where('status', $status)
                    ->orderBy('student_routestation.assigned_at', 'DESC')
                    ->findAll();
        }  
    }

    public function getAssignmentByStudent($student_id)
    {
        return $this->select('students.*, stations.staName, routes.rouName')
                    ->join('route_stations', 'route_stations.rsID = student_routestation.route_station_id')
                    ->join('routes', 'routes.rouID = route_station.route_id')
                    ->join('stations', 'stations.staID = route_stations.station_id')
                    ->join('students', 'students.stuID = student_routestation.student_id')
                    ->where('student_routestation.student_id', $student_id)
                    ->first();
    }
}