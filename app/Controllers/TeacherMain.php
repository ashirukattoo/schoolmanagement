<?php

namespace App\Controllers;

class TeacherMain extends BaseController{
    
    public function __construct() {
        helper(['url', 'form']);
    }
    /*==================================================================================================
        EMPLOYEE MODULE
    ===================================================================================================*/
    public function dashboard()  {
        $data = [
            'subjects' => $this->subjectModel->countAllResults(),
            'exams'    => $this->examModel->countAllResults(),
            'students' => $this->studentModel->countAllResults(),
            'employees' =>$this->employeeModel->countAllResults(),
            'pageTitle' => 'Dashboard',
        ];
        return view('teacher/dashboard', $data);
    } //End of dashboard

    /*==================================================================================================
        STUDENT MODULE
    ===================================================================================================*/
    //Render Students records
        public function view_students(){
            $data['students'] = $this->studentModel->getFullDetails();
            return view('teacher/students/index', $data);
        }

} 