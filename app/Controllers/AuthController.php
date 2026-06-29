<?php

namespace App\Controllers;

class AuthController extends BaseController{
    
    public function login(){
        return view('auth/login');
    }

    public function attemptLogin(){
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->employeeModel->where('empEmail', $email)->first();
        if (!$user) {
            $user = $this->guardianModel->where('guEmail', $email)->first();
        }

        if ($user && password_verify($password, $user['empPassword'])) {
            if($this->empRoles->isAdmin($user['empID']) == true){
                $role = 'Admin';
            }elseif($this->empRoles->isManager($user['empID']) == true){
                $role = 'Manager';
            }elseif($this->empRoles->isHead($user['empID']) == true){
                $role = 'Head';
            }elseif($this->empRoles->isDeputy($user['empID']) == true){
                $role = 'Deputy';
            }elseif($this->empRoles->isAcademic($user['empID']) == true){
                $role = 'Academic';
            }elseif($this->empRoles->isAccountant($user['empID']) == true){
                $role = 'Accountant';
            }else{
                $role = 'Guardian';
            }
            //$role = $user['empPosition'] ?? 'guardian';
            $this->session->set([
                'user_id' => $user['empID'] ?? $user['guID'],
                'user_name' => $user['empSurname'].', '.$user['empFname'] ?? $user['empname'],
                'role' => $role,
                'logged_in' => true
            ]);
            if ($role === 'Admin') {
                return redirect()->to('/admin/dashboard');
            }elseif($role === "Academic"){
                return redirect()->to('academic/index');
            }elseif ($role !== 'guardian' || $role !== 'student'){
                return redirect()->to('/home');
            }else{
               return redirect()->to('/dashboard'); 
           }
        } else {
            return redirect()->back()->with('error', 'Either email, password, or both are invalid.');
        }
    }

public function resetPassword($token){
    $db = \Config\Database::connect();
    $record = $db->table('password_resets')
        ->where('token', $token)
        ->where('expires_at >=', date('Y-m-d H:i:s'))
        ->get()
        ->getRow();

    if (!$record) {
        return redirect()->to('/login')->with('error', 'Invalid or expired link.');
    }

    helper(['form']);

    if ($this->request->getMethod() === 'post') {
        $password = $this->request->getPost('password');

        $this->employeeModel->update($record->employee_id, [
            'empPassword'   => password_hash($password, PASSWORD_DEFAULT),
            'empForceReset' => 0
        ]);

        // Delete token after use
        $db->table('password_resets')->where('id', $record->id)->delete();

        return redirect()->to('/login')->with('success', 'Password set successfully.');
    }

    return view('auth/reset_password', ['token' => $token]);
}


    
    
































    public function register(){
        return view('auth/register');
    } //End of Self register

    public function add(){
        $data = [
            'pageTitle' => 'Add New Employment',
        ];
        return view('employee/create', $data);
    } //End of adding Employee form

    public function add_route(){
        $data = [
            'pageTitle' => 'Add Route',            
            'regions' => $this->regionModel->getOrdered(),
            'activeRoutes' => $this->routeModel->getWithStatus()
        ];
        return view('tour/add_routes', $data);
    } //End of adding route form

    public function save_route(){
        $data = [
            'route' => $this->request->getPost('name'),
            'start' => $this->request->getPost('from'),
            'end' => $this->request->getPost('to')
        ];
        $result = $this->routeModel->insertRoute($data);
        if(isset($result['error'])){
            return redirect()->back()->with('error', $result['error']);
        }
        $name = $this->request->getPost('name');
        return redirect()->back()->with('success', $name.' Registered Successfully');
    }
    public function del_route($value=''){
        $route = $this->request->getGet('del_id');
        if ($this->routeModel->delete($route)) {
            return redirect()->back()->with('delete', 'Deleted successfully');
        }else{
            return redirect()->back()->with('undelete', 'Something Went Wrong! Failed to Delete');
        }
    }

    public function add_station(){
        $data = [
            'pageTitle' => 'Record New Station',
            'regions' =>$this->regionModel->findAll(),
            'districts' =>$this->districtModel->getAll(),
            'wards' =>$this->wardModel->getAll(),
            'streets' =>$this->streetModel->getAll(),
            'recStations' =>$this->stationModel->getAll(),
        ];
        return view('tour/add_stations', $data);
    } //End of adding Employee form

    public function attemptRegister(){
        $role = $this->request->getPost('role');
        $data = [
            'empEmail' => $this->request->getPost('email'),
            'empPassword' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'empFname' => $this->request->getPost('fname'),
            'empMname' => $this->request->getPost('mname'),
            'empSurname' => $this->request->getPost('lname'),
            'empOccupasion' => $this->request->getPost('occupation'),
            'empSex' => $this->request->getPost('sex'),
            'empRegisterd' => date('Y-m-d H:i:s'),
            'empStatus' => 'active',
        ];

        if ($role == 'employee') {
            $this->employeeModel->insert($data);
        } else {
            $this->guardianModel->insert([
                'empEmail' => $data['empEmail'],
                'empPassword' => $data['empPassword'],
                'empname' => $data['empFname'] . ' ' . $data['empSurname'],
                'guSex' => $data['empSex'],
                'guOccupasion' => $data['empOccupasion'],
                'guRegisterd' => $data['empRegisterd'],
                'guStatus' => $data['empStatus'],
            ]);
        }

        return redirect()->to('/')->with('success', 'Account created successfully!');
    } //End of attemptRegister
    public function save_station(){
        $data = [
            'staName' => $this->request->getPost('station'),
            'staGps' => null,
            'staBefore' => null,
            'staAfter' => null,
            'staCreated' => date('Y-m-d H:i:s'),
            'staStatus' =>  'active',
        ];
        $this->stationModel->insert($data);
        return redirect()->back()->with('success', 'New Station ('.$data['staName'].') Recorded successfully!');
    } //End of saving station

    public function vehicles($value='') {
        $data = [
            'pageTitle' => 'Vehicles',
            'regions' =>$this->regionModel->findAll(),
            'districts' =>$this->districtModel->getAll(),
            'wards' =>$this->wardModel->getAll(),
            'streets' =>$this->streetModel->getAll(),
            'recStations' =>$this->stationModel->getAll(),
        ];
        return view('tour/add_vehicles', $data);
    }

    public function dashboard()  {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }else{
            $data = [
                'students' => $this->studentModel->countAllResults(),
                'employees' => $this->employeeModel->countAllResults(),
                'routes' => $this->routeModel->countAllResults(),
                'vehicles' => $this->vehicleModel->countAllResults(),
                'pageTitle' => 'Dashboard',
                //'vehicles' => $this->studentModel->countAllResults(),
            ];
            return view('auth/dashboard', $data);
        }  
    } //End of dashboard

    public function employee(){
        $data  = [
            'employees' => $this->employeeModel->activeEmployee(),
        ];
        return view('employee/index', $data);
    }

    public function manageEmp(){
        $data  = [
            'employees' => $this->employeeModel->activeEmployee(),
        ];
        return view('employee/manage', $data);
    }

    public function logout(){
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'You have been logged out.');
    } //End of Logout
}