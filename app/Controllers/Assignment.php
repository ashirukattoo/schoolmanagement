<?php

namespace App\Controllers;

use App\Models\ClassModel;
use App\Models\RouteModel;
use App\Models\StudentModel;
use App\Models\RouteStationModel;
use App\Models\StudentRouteStationModel;
use CodeIgniter\Controller;

class Assignment extends Controller{
    public function config(){
        $classModel = new ClassModel();
        $routeModel = new RouteModel();

        $data = [
            'classes' => $classModel->findAll(),
            'routes'  => $routeModel->findAll(),
        ];

        return view('students/config', $data);
    }

    public function fetchData(){
        $studentModel = new StudentModel();
        $routeStationModel = new RouteStationModel();

        $class_id = $this->request->getPost('class_id');
        $route_id = $this->request->getPost('route_id');

        $students = $studentModel->where('class_id', $class_id)->findAll();
        $stations = $routeStationModel->getRouteStations($route_id);

        return $this->response->setJSON([
            'students' => $students,
            'stations' => $stations,
        ]);
    }

    public function save() {
        $studentRouteStationModel = new StudentRouteStationModel();
        $assignments = $this->request->getPost('assignments');

        if (!$assignments) {
            return redirect()->back()->with('error', 'No data submitted.');
        }

        foreach ($assignments as $assign) {
            if (empty($assign['student_id']) || empty($assign['route_station_id'])) {
                continue; // skip invalid entries
            }

            $studentRouteStationModel->insert([
                'student_id'       => $assign['student_id'],
                'route_station_id' => $assign['route_station_id'],
                'srCreated'        => date('Y-m-d H:i:s'),
                'srStatus'         => 1
            ]);
        }

        return redirect()->to('students/configu')->with('success', 'Assignments saved successfully!');
    }

}
