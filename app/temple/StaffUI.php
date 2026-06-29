<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StudentModel;
use App\Models\UserRoleModel;
use CodeIgniter\Controller;

class StaffUI extends Controller
{
    protected $userModel;
    protected $studentModel;
    protected $roleModel;
    protected $session;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->userModel = new UserModel();
        $this->studentModel = new StudentModel();
        $this->roleModel = new UserRoleModel();
        $this->session = session();
    }

    /**
     * Staff dashboard view
     */
    public function dashboard()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('dashboard', [
            'title' => 'Staff Dashboard',
            'user'  => $this->session->get(),
        ]);
    }

    /**
     * View and manage students
     */
    public function manageStudents()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $students = $this->studentModel
            ->select('students.*, users.fname, users.mname, users.surname')
            ->join('users', 'users.id = students.user_id')
            ->where('students.status', 'active')
            ->findAll();

        return view('Academic/results', [
            'title' => 'Manage Students',
            'students' => $students
        ]);
    }

    /**
     * Admit new student (show form)
     */
    public function admitStudentForm()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('admit_student', [
            'title' => 'Admit New Student'
        ]);
    }

    /**
     * Handle admission form submission
     */
    public function admitStudentSubmit()
    {
        $request = service('request');
        $db = db_connect();
        $db->transStart();

        try {
            // Create system user account
            $userData = [
                'fname'         => $request->getPost('fname'),
                'mname'         => $request->getPost('mname'),
                'surname'       => $request->getPost('surname'),
                'username'      => strtolower($request->getPost('fname') . '.' . $request->getPost('surname')),
                'email'         => $request->getPost('email'),
                'password_hash' => password_hash('123456', PASSWORD_DEFAULT), // default password
            ];

            $userId = $this->userModel->insert($userData);

            // Assign student role
            $this->roleModel->insert([
                'user_id' => $userId,
                'role_id' => 3 // role 3 = student
            ]);

            // Register student record
            $studentData = [
                'user_id'        => $userId,
                'class_id'       => $request->getPost('class'),
                'dob'            => $request->getPost('dob'),
                'gender'         => $request->getPost('sex'),
                'status'         => 'active',
                'admission_date' => date('Y-m-d'),
            ];

            $this->studentModel->insert($studentData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error while saving student data.');
            }

            return redirect()->to('/staff/students')->with('success', 'Student admitted successfully.');

        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Manage teacher/staff records
     */
    public function manageTeachers()
    {
        // Placeholder: Later link to TeacherModel
        return view('admit_teacher', [
            'title' => 'Staff Registration'
        ]);
    }

    /**
     * Add or review examinations
     */
    public function manageExams()
    {
        return view('Academic/exam_review', [
            'title' => 'Examination Management'
        ]);
    }

    /**
     * Review results of students
     */
    public function viewResults()
    {
        return view('Academic/results', [
            'title' => 'Student Results'
        ]);
    }

    /**
     * Logout staff
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully.');
    }
}