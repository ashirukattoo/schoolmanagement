<?php

namespace App\Controllers;

use App\Models\StudentRouteStationModel;
use App\Models\StudentModel;
use App\Models\EmployeeModel;
use App\Models\GuardianModel;
use App\Models\RouteModel;
use App\Models\ClassModel;
use App\models\RouteStationModel;

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller{
    protected $studentModel;
    protected $guardianModel;
    protected $employeeModel;
    protected $routeModel;
    protected $classModel;
    protected $routeStationModel;
    protected $session;

    public function __construct(){
        helper(['form','url']);
        $this->studentModel = new StudentModel();
        $this->employeeModel = new EmployeeModel();
        $this->guardianModel = new GuardianModel();
        $this->routeModel = new RouteModel();
        $this->classModel = new ClassModel();
        $this->routeStationModel = new RouteStationModel();
        $this->session = session();
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }
    }

    /* ======================================================================================
                    STUDENTS MANGEMENT 
    =======================================================================================*/
    public function index(){
        $data['students'] = $this->studentModel->getFullDetails();
        return view('students/index', $data);
    }

    public function create(){
        $data['guardians'] = $this->guardianModel->findAll();
        $data['routes'] = $this->routeModel->findAll();
        $data['pageTitle'] = 'Add Students';
        return view('students/create', $data); 
    }

    public function store(){
        $data = [
            'stuFname' => $this->request->getPost('fname'),
            'stuMname' => $this->request->getPost('mname'),
            'stuSurname' => $this->request->getPost('lname'),
            'stuSex' => $this->request->getPost('sex'),
            'stuDob' => $this->request->getPost('dob'),
            'class_id' => $this->request->getPost('class'),
            'guardian_id' => $this->request->getPost('guardian_id')
        ];
        $result = $this->studentModel->insertStudent($data);
        if(isset($result['error'])){
            return redirect()->back()->with('error', $result['error']);
        }
        $name = $this->request->getPost('fname').' '.$this->request->getPost('lname');
        return redirect()->to('/students/add')->with('success', $name.' Registered Successfully');
    }

    public function upload(){
        $data['pageTitle'] = 'Bulk Student Upload';
        return view('students/upload', $data);
    }

    protected function processCSV($path) {
        $handle = fopen($path,"r");
        $header = fgetcsv($handle, 1000, ",");
        while(($row = fgetcsv($handle, 1000, ",")) !== FALSE){
            $data = array_combine($header, $row);
            $this->saveRow($data);
        }
        fclose($handle);
    }

    protected function processXLSX($path) {
        $spreadsheet = IOFactory::load($path);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

        $header = array_shift($sheetData); // first row as header
        foreach($sheetData as $row){
            $data = [];
            foreach($header as $col => $key){
                $data[$key] = $row[$col];
            }
            $this->saveRow($data);
        }
    }

    protected function saveRow($data, $type = 'student'){
        // Decide which table based on a column, e.g., "type"
        if(isset($type)){
            switch(strtolower($type)){
                case 'student':
                    $this->studentModel->insert([
                        'stuFname'=>$data['stuFname'],
                        'stuMname'=>$data['stuMname'],
                        'stuSurname'=>$data['stuSurname'],
                        'stuSex'=>$data['stuSex'],
                        'stuDob'=>$data['stuDob'],
                        'class_id'=>$data['class_id'] ?? null,
                        'guardian_id'=>$data['guardian_id'] ?? null,
                        'stuStatus'=>'active'
                    ]);
                    break;
                case 'employee':
                    $this->employeeModel->insert([
                        'empFname'=>$data['empFname'],
                        'empMname'=>$data['empMname'],
                        'empSurname'=>$data['empSurname'],
                        'empSex'=>$data['empSex'],
                        'empDob'=>$data['empDob'],
                        'empOccupasion'=>$data['empOccupasion'],
                        'empEmail'=>$data['empEmail'],
                        'empPassword'=>password_hash($data['empPassword'],PASSWORD_DEFAULT),
                        'empHired'=>$data['empHired'] ?? date('Y-m-d'),
                        'empStatus'=>'active'
                    ]);
                    break;
                case 'guardian':
                    $this->guardianModel->insert([
                        'empname'=>$data['empname'],
                        'guSex'=>$data['guSex'],
                        'empDob'=>$data['empDob'],
                        'guOccupasion'=>$data['guOccupasion'],
                        'empEmail'=>$data['empEmail'],
                        'empPassword'=>password_hash($data['empPassword'],PASSWORD_DEFAULT),
                        'guRegisterd'=>date('Y-m-d'),
                        'guStatus'=>'active'
                    ]);
                    break;
            }
        }
    }

    public function import(){
        $file = $this->request->getFile('file');

        if(!$file->isValid()){
            return redirect()->back()->with('error','File upload failed');
        }

        $ext = $file->getClientExtension();
        if($ext === 'csv' || $ext === 'txt'){
            $this->processCSV($file->getTempName());
        } else if(in_array($ext,['xlsx','xls'])){
            $this->processXLSX($file->getTempName());
        } else {
            return redirect()->back()->with('error','Invalid file type');
        }

        return redirect()->back()->with('success','File uploaded successfully!');
    }

    public function edit($id) {
        $data['student'] = $this->studentModel->find($id);
        $data['guardians'] = $this->guardianModel->findAll();
        return view('students/edit', $data);
    }

    public function update($id){
        $this->studentModel->update($id, [
            'stuFname' => $this->request->getPost('stuFname'),
            'stuMname' => $this->request->getPost('stuMname'),
            'stuSurname' => $this->request->getPost('stuSurname'),
            'stuSex' => $this->request->getPost('stuSex'),
            'stuDob' => $this->request->getPost('stuDob'),
            'class_id' => $this->request->getPost('class_id'),
            'guardian_id' => $this->request->getPost('guardian_id'),
            'stuStatus' => $this->request->getPost('stuStatus')
        ]);
        return redirect()->to('/students');
    }


    public function delete($id) {
        $this->studentModel->delete($id);
        return redirect()->to('/students');
    }

    // 🔍 Search students
    public function search(){
        $keyword = $this->request->getGet('q');
        $data['students'] = $this->studentModel->search($keyword);
        return view('students/index', $data);
    }

    /* =====================================================================================
            ROUTES/STATIONS WITH  STUDENTS MANAGEMENT
    =======================================================================================*/
    public function assignRoute($class = 0){
        if ($class === 0) {
            $data = [
                'pageTitle' => 'Students\' Allocation  on Route\'s Stations ',
                'classes' => $this ->classModel ->findAll(),
                'assignedStudents' => $this->studentModel->getWithStudentRoute()
            ];
            return view('students/manage', $data);
        }else{
            $data = [
                'pageTitle' => 'Students\' Allocation  on Route\'s Stations ',
                'classes' => $this ->classModel ->findAll(),
                'assignedStudents' => $this->studentModel->getFullDetailsByClass($class)
            ];
            return view('students/manage', $data);
        }
            
    }

    public function getSpecificStudents(){
        $class = $this->request->getPost('class');
        return $this -> assignRoute($class);
    }

    public function save_assignRoute(){
        $data = [
            'student_id' =>$this->request->getPost('student'),
            'rote_id' =>  $this->request->getPost('route'),
            'srCreated' => date('Y-m-d H:i:s'),
            'srCreatedBy' => $this->session->get('user_id'),
            'srStatus' =>'Active'
        ];
        $this->studentModel->insert($data);

        return redirect()->back()->with('success', 'Student assigned to route Successfully');
    }

    public function add_studentOnRoute() {
        $data['classes'] = $this->classModel->findAll();
        $data['students'] = $this->studentModel->findAll();
        $data['routes'] = $this->routeModel->findAll();
        $data['routeStations'] = $this->routeStationModel->getAllRouteStations();
        $data['employees'] = $this->employeeModel->findAll();

        return view('students/assign_student_route', $data);
    }

    public function getStationsByRoute($route_id)
    {
        $db = db_connect();
        $query = $db->query("SELECT * FROM route_stations 
                             JOIN stations ON stations.staID = route_stations.station_id
                             WHERE route_id = ?", [$route_id]);
        $stations = $query->getResultArray();

        $html = '<option value="">Select Station</option>';
        foreach ($stations as $st) {
            $html .= '<option value="'.$st['staID'].'">'.$st['staName'].'</option>';
        }
        return $html;
    }

    public function getStudentsByClass($class_id)   {
        $students = $this->studentModel->where('class_id', $class_id)->findAll();
        $html = '<table class="table table-bordered">';
        $html .= '<thead><tr><th><input type="checkbox" id="checkAll"></th><th>Name</th><th>Sex</th><th>Status</th></tr></thead><tbody>';
        foreach ($students as $s) {
            $html .= '<tr>
                        <td><input type="checkbox" name="student_ids[]" value="'.$s['stuID'].'"></td>
                        <td>'.$s['stuFname'].' '.$s['stuSurname'].'</td>
                        <td>'.$s['stuSex'].'</td>
                        <td>'.$s['stuStatus'].'</td>
                      </tr>';
        }
        $html .= '</tbody></table>';
        $html .= '<script>$("#checkAll").on("click", function(){ $("input[name=\'student_ids[]\']").prop("checked", this.checked); });</script>';
        return $html;
    }

    public function storeBulk()
    {
        $studentIDs = $this->request->getPost('student_ids');
        $routeID = $this->request->getPost('route_id');
        $stationID = $this->request->getPost('station_id');
        $assignedBy = $this->request->getPost('assigned_by');

        if ($studentIDs) {
            $db = db_connect();
            foreach ($studentIDs as $stuID) {
                $routeStation = $db->query("SELECT rsID FROM route_station WHERE route_id=? AND station_id=?", [$routeID, $stationID])->getRow();
                if ($routeStation) {
                    $this->studentRouteStationModel->insert([
                        'student_id' => $stuID,
                        'route_station_id' => $routeStation->rsID,
                        'assigned_by' => $assignedBy,
                    ]);
                }
            }
        }

        return redirect()->to('/student_routestation')->with('success', 'Students assigned successfully.');
    }

}