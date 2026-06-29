<?php

namespace App\Controllers;
use App\Models\StudentRouteStationModel;
use App\Models\TourRouteModel;
use App\Models\StudentModel;
use App\Models\RouteModel;
use App\Models\VehicleModel;
use App\Models\EmployeeModel;
use App\Models\TourModel;
use CodeIgniter\Controller;

class TourController extends Controller{
    protected $studentRouteStationModel;
    protected $tourRouteModel;
    protected $studentModel;
    protected $routeModel;
    protected $vehicleModel;
    protected $employeeModel;
    protected $tourModel;

    public function __construct(){
        helper(['form','url']);
        $this->studentRouteStationModel = new StudentRouteStationModel();
        $this->tourRouteModel = new TourRouteModel();
        $this->studentModel = new StudentModel();
        $this->routeModel = new RouteModel();
        $this->vehicleModel = new VehicleModel();
        $this->employeeModel = new EmployeeModel();
        $this->tourModel = new TourModel();
    }

    // Assign students to routes
    public function assignStudent(){
        $post = $this->request->getPost();
        $this->studentRouteStationModel->insert([
            'student_id' => $post['student_id'],
            'route_id' => $post['route_id'],
            'srCreated' => date('Y-m-d H:i:s'),
            'srCreatedBy' => auth()->user()->id,
            'srStatus' => 'active'
        ]);
        return redirect()->back()->with('success','Student assigned to route.');
    }

    // Assign vehicle + employee to route (TourRoute)
    public function assignVehicleEmployee(){
        $post = $this->request->getPost();
        $this->tourRouteModel->insert([
            'tour_id' => $post['tour_id'],
            'route_id' => $post['route_id'],
            'employee_id' => $post['employee_id'],
            'vehicle_id' => $post['vehicle_id'],
            'trDeparture' => $post['trDeparture'],
            'trArrival' => $post['trArrival'],
            'trPosition' => $post['trPosition'],
            'trStatus' => 'active'
        ]);
        return redirect()->back()->with('success','Vehicle and Employee assigned to route.');
    }

    // Show all assignments (It work)
    public function index(){
        $data['studentRoutes'] = $this->studentRouteStationModel->getAllAssignments();
        $data['tourRoutes'] = $this->tourRouteModel->getFullAssignments();
        return view('tour/index',$data);
    }
}