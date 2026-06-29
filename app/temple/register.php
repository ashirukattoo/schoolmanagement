<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserRoleModel;
use App\Models\StudentModel;
use CodeIgniter\Controller;

class Register extends Controller
{
    protected $userModel;
    protected $roleModel;
    protected $studentModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new UserRoleModel();
        $this->studentModel = new StudentModel();
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    /**
     * Show student admission form (View)
     */
    public function admitStudent()
    {
        return view('admit_student');
    }

    /**
     * Add new user and return ID
     */
    protected function create_user($data)
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']); // Remove plain password

        $this->userModel->insert($data);
        return $this->userModel->getInsertID();
    }

    /**
     * Add new student (called after user creation)
     */
    protected function add_student($user_id, $data)
    {
        $studentData = [
            'user_id'       => $user_id,
            'class_id'      => $data['class_id'],
            'section_id'    => $data['section_id'] ?? null,
            'dob'           => $data['dob'],
            'gender'        => $data['gender'],
            'admission_no'  => $data['admission_no'] ?? uniqid('ADM'),
            'guardian_id'   => $data['guardian_id'] ?? null,
            'admission_date'=> date('Y-m-d'),
            'status'        => 'active',
        ];

        $this->studentModel->insert($studentData);
        return $this->studentModel->getInsertID();
    }

    /**
     * Assign a role to a user
     */
    protected function assign_role($user_id, $role_id)
    {
        $roleData = [
            'user_id' => $user_id,
            'role_id' => $role_id
        ];

        return $this->roleModel->insert($roleData);
    }

    /**
     * Handle POST form submission for new student
     */
    public function saveStudent()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/register/admitStudent');
        }

        $data = $this->request->getPost();

        // Step 1: create user
        $userData = [
            'fname'    => $data['fname'],
            'mname'    => $data['mname'],
            'surname'  => $data['surname'],
            'username' => strtolower($data['fname']) . rand(100, 999),
            'email'    => $data['email'] ?? null,
            'password' => '123456', // default password (should prompt change)
        ];
        $user_id = $this->create_user($userData);

        // Step 2: assign role (e.g. student role_id = 3)
        $this->assign_role($user_id, 3);

        // Step 3: register student
        $studentData = [
            'class_id' => $data['class'],
            'dob'      => $data['dob'],
            'gender'   => $data['sex'],
        ];
        $this->add_student($user_id, $studentData);

        return redirect()->to('/register/admitStudent')->with('success', 'Student registered successfully!');
    }

    /* --- Future extensions --- */
    public function add_role() {}
    public function add_staff($user_id) {}
    public function add_class() {}
    public function add_level() {}
    public function add_room() {}
    public function add_exam() {}
    public function add_term() {}
    public function add_academicYear() {}
    public function compile_result() {}
    public function add_result() {}
    public function define_grade($level, $class, $exam) {}
}