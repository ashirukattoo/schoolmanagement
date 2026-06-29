<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\StudentModel;
use App\Models\GuardianModel;
use App\Models\RouteModel;

class Home extends BaseController
{
    public function index(){
        return view('auth/login');  // your custom login page
    }
}
