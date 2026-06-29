<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');     // login page (work)
$routes->get('/login', 'Home::index');
$routes->get('/logout', 'AuthController::logout');
$routes->post('/attemptLogin', 'AuthController::attemptLogin');
$routes->get('/register', 'AuthController::register');

//ADMIN ROUTES
$routes->get('admin/streams/promote/(:num)', 'AdminMain::promoteStream/$1');
$routes->get('admin/students/promote/', 'AdminMain::promoteOLevel');
$routes->get('/admin/dashboard', 'AdminMain::dashboard'); //Home page for Admin
$routes->get('/admin/add/students', 'AdminMain::create_student'); //Add student UI
$routes->post('/admin/save/students', 'AdminMain::store_student'); //Add Studend Backend
//$routes->post('/admin/import/students', 'AdminMain::import');  //Upload Students 
$routes->get('/admin/view/students', 'AdminMain::view_students'); //View Students List
$routes->get('/admin/manage/students', 'AdminMain::manage_students'); //Manage students Record UI
$routes->get('/admin/del/student/(:num)', 'AdminMain::erase_student/$1'); //Backend Deletetion 
$routes->get('/admin/manage/student', 'AdminMain::manage_student'); //Manage student Record backend
$routes->post('/admin/update/student', 'AdminMain::update_student'); //Update student's Record Backend
$routes->get('admin/template/students', 'AdminMain::downloadStudentTemplate');// Download student upload template
$routes->post('admin/import/students', 'AdminMain::importStudents');// Upload/import students
$routes->get('admin/students/attendance/(:any)', 'AdminMain::recordAttendance/$1');
$routes->post('admin/students/attendance/save', 'AdminMain::saveAttendance');
$routes->post('/admin/guardian/student', 'AdminMain::save_guardian'); //Update student's Record Backend
$routes->get('/admin/guardian_list/student', 'AdminMain::getAllGurdian'); //Update student's Record Backend
$routes->post('admin/save/ac_year', 'AdminMain::saveAcademicYear');
//Add Academic Year backend
$routes->get('/admin/add/stream', 'AdminMain::add_stream'); //Add stream UI
$routes->post('/admin/save/stream', 'AdminMain::store_stream'); //Add stream backend
$routes->get('/admin/view/stream', 'AdminMain::streams'); //View streams UI
$routes->get('/admin/stream/activate/(:num)', 'AdminMain::activate_stream/$1');
$routes->get('/admin/stream/deactivate/(:num)', 'AdminMain::deactivate_stream/$1');
$routes->get('/admin/report/stream', 'AdminMain::getStreamReport'); //Get Reports Concerned With Streams
$routes->get('/admin/assign/stream/employees', 'AdminMain::assign_stream_master');
$routes->post('/admin/assign/stream/employees', 'AdminMain::assign_stream_master');
$routes->get('/admin/update/stream/employees/(:segment)/(:segment)', 'AdminMain::update_assign_stream_master/$1/$2');
$routes->get('/admin/add/class', 'AdminMain::add_class'); //Add Class UI
$routes->post('/admin/add/class', 'AdminMain::save_class'); //Add Class UI
$routes->get('/admin/view/class', 'AdminMain::classes'); //View Class UI
$routes->get('admin/template/subject', 'AdminMain::downloadSubjectTemplate');
$routes->post('admin/import/subject', 'AdminMain::importSubjects');
$routes->get('/admin/add/subject', 'AdminMain::add_Subject'); //Add Subjects UI
$routes->post('/admin/save/subject', 'AdminMain::store_subject'); //Add Subjects UI
$routes->get('/admin/view/subjects', 'AdminMain::subjects'); //Add Subjects UI
$routes->get('/admin/del/subject/(:num)', 'AdminMain::erase_subject/$1'); //Backend Deletetion 
$routes->get('/admin/manage/subjects', 'AdminMain::manage_subjects'); //Manage Subjects UI
$routes->get('/admin/associate/subject', 'AdminMain::add_subjectsToStream'); //Assign Subject to Streams
$routes->post('/admin/associate/subject', 'AdminMain::save_subjectsToStream');//Save subjects on stream Backend
//==============================================================================
// HR Module Routes
//==============================================================================
$routes->get('admin/salary-grades', 'AdminMain::salaryGrades');
$routes->post('admin/saveSalaryGrade', 'AdminMain::saveSalaryGrade');
$routes->post('admin/process/payroll', 'AdminMain::processPayroll');
$routes->get('admin/payroll/setup', 'AdminMain::payrollSetup');// Load payroll setup page
$routes->get('admin/employee/(:num)/deductions', 'AdminMain::manageEmployeeDeductions/$1');
$routes->post('admin/employee/deductions/save', 'AdminMain::saveEmployeeDeductions');
$routes->get('admin/payroll/list', 'AdminMain::payrollList');
$routes->get('admin/payroll/view/(:num)', 'AdminMain::viewPayroll/$1');
$routes->get('admin/payroll/payslip/(:num)', 'AdminMain::payslip/$1');
$routes->get('admin/payroll/reports', 'AdminMain::payrollReports');
$routes->get('admin/payroll/reports', 'AdminMain::payrollReports');
$routes->get('admin/payroll/reports/pdf', 'AdminMain::exportPayrollPdf');
$routes->get('admin/payroll/reports/excel', 'AdminMain::exportPayrollExcel');
$routes->get('admin/employee/template', 'AdminMain::downloadEmployeeTemplate');
$routes->post('admin/import/employee', 'AdminMain::importEmployees');
$routes->match(['get', 'post'], 'auth/reset-password/(:segment)', 'AuthController::resetPassword/$1');
$routes->get('/admin/add/employees', 'AdminMain::add_employee'); //Add employee
$routes->post('/admin/add/employees', 'AdminMain::save_employee'); //Backend save employee
$routes->get('/admin/del/employee/(:num)', 'AdminMain::erase_employee/$1'); //Backend Deletetion
$routes->get('/admin/edit/employee/(:num)', 'AdminMain::edit_employee/$1');
$routes->post('admin/edit/employee', 'AdminMain::updateEmployee');
$routes->get('/admin/more/employee/(:num)', 'AdminMain::about_employee/$1');
$routes->get('/admin/view/employees', 'AdminMain::view_employee'); //Render employee
$routes->get('/admin/manage/employees', 'AdminMain::manageEmp'); //Manage employee record
$routes->get('/admin/assign/subjects/employees', 'AdminMain::assign_subjectsToEmployee');
$routes->post('/admin/assign/subjects/employees', 'AdminMain::assign_subjectsToEmployee');

//TEACHER Routes
$routes->get('/home', 'TeacherMain::dashboard'); //Home page for Teacher
$routes->get('/view/students', 'TeacherMain::view_students'); //View Students List
$routes->get('/manage/students', 'TeacherMain::manage_students'); //Manage students Record UI
$routes->get('/manage/student', 'TeacherMain::manage_student'); //Manage student Record backend
$routes->post('/update/student', 'TeacherMain::update_student'); //Update student's Record Backend


//AJAX RESPONSES
$routes->get('/admin/getSubjectsByClass', 'AdminMain::getSubjectsByClass');
$routes->post('admin/get-terms-by-year', 'AdminMain::getTermsByYear');


// ===============================
// Examination Results Management
// ===============================
$routes->get('/admin/view/exams', 'AdminMain::exams'); //View Exams List
$routes->get('/admin/setting/exams', 'AdminMain::exam_setup'); //Setting Examinations
$routes->post('/admin/save/exams', 'AdminMain::saveExam'); //Setting Examinations
$routes->get('/admin/record/exams', 'AdminMain::record_score'); //Record Exams Score
$routes->get('/admin/score/exams/(:any)/(:any)', 'AdminMain::score_sheet/$1/$2'); //Generate Exams Score Sheet
$routes->get('/admin/download_score_template/(:any)/(:any)', 'AdminMain::download_score_template/$1/$2'); //Generate Exams Score Sheet template
$routes->get('/admin/export_students_list/(:any)', 'AdminMain::export_students_list/$1'); //Generate Students List by class
$routes->get('/admin/export_students_list', 'AdminMain::export_students_list'); //Generate Students List school wise
$routes->get('/admin/exams/record', 'AdminMain::record_score'); // record/upload page
$routes->post('/admin/exams/upload', 'AdminMain::up_score_sheet'); // upload Excel
$routes->get('/admin/exams/compile/(:any)', 'AdminMain::compile_results/$1'); // compile results
$routes->get('/admin/compile/results/(:any)/(:num)', 'AdminMain::compile_exam_results/$1/$2');
$routes->get('/admin/edit/exams/(:any)', 'AdminMain::exam_edit/$1');
$routes->get('/admin/results/class/(:any)/(:num)', 'AdminMain::view_class_results/$1/$2');
$routes->get('/admin/export/excel/(:any)/(:num)', 'AdminMain::export_class_results_excel/$1/$2');
$routes->get('/admin/export/pdf/(:num)/(:any)', 'AdminMain::export_class_results_pdf/$1/$2');
$routes->get('/admin/report_card/(:segment)/(:num)', 'AdminMain::report_card/$1/$2');
$routes->get('/admin/report_cards_class/(:segment)/(:num)', 'AdminMain::generate_report_card/$1/$2');
$routes->post('/admin/report_cards_class/(:segment)/(:num)', 'AdminMain::generate_report_card/$1/$2');
$routes->get('/admin/report_card/pdf/(:segment)/(:num)', 'AdminMain::report_card_pdf/$1/$2');
$routes->post('/admin/results/view', 'AdminMain::view_results');
$routes->get('/admin/view/results', 'AdminMain::view_result');
$routes->get('admin/analytics/generate/(:segment)', 'AdminMain::generateAnalytics/$1');
$routes->get('admin/exam/analyse/(:segment)', 'AdminMain::generate/$1');
$routes->get('admin/print_analytice/(:any)', 'AdminMain::print_analytice/$1');
$routes->get('admin/results/analytics/(:segment)', 'AdminMain::view_analytice/$1');
$routes->get('admin/analytics/(:segment)', 'AdminMain::viewAnalytics/$1');
$routes->get('analytics/delete/(:segment)', 'AdminMain::deleteAnalytics/$1');


/*=================================================================
   TRANSPORTATION MODULE ROUTES
  =================================================================*/
$routes->get('/admin/trans/routes', 'AdminMain::add_route');
$routes->post('/admin/trans/route', 'AdminMain::save_route'); //Add the Routes
$routes->get('/admin/trans/route/edit/(:num)', 'AdminMain::edit_route/$1'); //Edit the Routes Information
$routes->post('/admin/trans/route/add/station/(:num)', 'AdminMain::save_stationOnRoute/$1');
$routes->get('/admin/trans/tours', 'AdminMain::tours'); //Tours of Students
$routes->post('/admin/trans/tours/add', 'AdminMain::add_tour'); //Add Tours
$routes->post('/admin/trans/tours/assign', 'AdminMain::assignTourOnRoute'); //Assignment of Tour on Route with Details
$routes->get('/admin/trans/config/students', 'AdminMain::config');
$routes->post('/admin/trans/assignments/fetchData', 'AjaxMain::fetchUnassignedStudents');
$routes->post('/admin/trans/assignments/save', 'AdminMain::saveAssignedStudent');
$routes->get('/admin/trans/stations', 'AdminMain::add_station');
$routes->post('/admin/trans/stations/add', 'AdminMain::save_station'); //Save the stationary in db
$routes->get('/admin/trans/stations/students', 'AdminMain::studentOnRoute');
$routes->get('/admin/trans/stations/students/assign_route', 'AdminMain::assignRoute');
$routes->get('/admin/trans/buses', 'AdminMain::vehicles');
$routes->post('/admin/trans/buss/add', 'AdminMain::store_vehicle');

/*=================================================================
   AJAX ROUTES
==================================================================*/
$routes->get('ajax/get-districts/(:num)', 'AjaxMain::getDistricts/$1');
$routes->get('ajax/get-wards/(:num)', 'AjaxMain::getWards/$1');
$routes->get('ajax/get-streets/(:num)', 'AjaxMain::getStreets/$1');
$routes->get('ajax/get-terms/(:segment)', 'AjaxMain::getTerms/$1');


$routes->group('admin', function($routes){
    $routes->get('academic-years', 'AcademicYears::index');
    $routes->post('academic-years/create', 'AcademicYears::create');
    $routes->get('academic-years/current/(:segment)', 'AcademicYears::setCurrent/$1');

    $routes->get('terms', 'AcademicYears::indexx');
    $routes->get('terms/(:segment)', 'AcademicYears::terms/$1');
    $routes->post('terms/create', 'AcademicYears::createe');
    $routes->get('terms/current/(:segment)', 'AcademicYears::setCurrentt/$1');
});


/*===================================================================
   ACADEMIC SHEET ROUTES
===================================================================*/
$routes->group('academic', function($routes){
    $routes->get('index', 'AcademicSheet::dashboard'); //Dashboard
    $routes->get('students/view', 'AcademicSheet::manage_students'); //
});

/*====================================================================
      SHORT MESSAGE SERVICE
====================================================================*/
$routes->group('sms', function($routes){
   $routes->get('', 'Sms::index');
   $routes->post('send', 'Sms::send');
   $routes->get('process', 'Sms::processQueue');
   $routes->get('history', 'Sms::history');
   $routes->get('exam', 'Sms::examResults');
   $routes->post('fetch-exam-recipients', 'Sms::fetchExamRecipients');
   $routes->post('queue-exam-results', 'Sms::queueExamResultSMS');
   $routes->post('fetch-class-exams', 'Sms::fetchClassExams');
   $routes->get('queue', 'Sms::queue');
   $routes->get('send-single/(:num)', 'Sms::sendSingle/$1');
   $routes->get('retry/(:num)', 'Sms::retrySMS/$1');
   $routes->get('bulk', 'Sms::bulkSMS');
   $routes->post('fetch-bulk-recipients', 'Sms::fetchBulkRecipients');
   $routes->post('queue-bulk-sms', 'Sms::queueBulkSMS');
   $routes->post('check_phone_validity', 'Sms::checkPhoneValidity');
});
$routes->get('test-sms', 'Sms::testSMS');

