<?php

namespace App\Controllers;
use App\Libraries\PdfService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;
use CodeIgniter\I18n\Time;

class AdminMain extends BaseController{
    protected $db;
    protected $smsService;
    public function __construct() {
        helper(['url', 'form']);
        $this->db = \Config\Database::connect();
        $this->smsService = \Config\Services::smsService();
    }
    /*======================================================================================
        EMPLOYEE MODULE
    ======================================================================================*/
        public function dashboard()  {    
            // Counts
            $students = $this->studentModel->countExcept(['Completed', 'Transfered', 'Inactive']);
            $employees = $this->employeeModel->countAllResults();
            $subjects = $this->subjectModel->countAllResults();
            $exams = $this->examModel->countAllResults();

            // Enrollment grouped by Stream
            $enrollment = $this->studentModel
                ->select('CONCAT(cl.named, "-", ss.sName) AS stream, cl.numeral, COUNT(students.stuID) AS total')
                ->join('streams ss', 'ss.sid = students.stream_id', 'left')
                ->join('classes cl', 'cl.cid = ss.class_id')
                ->where('stuStatus', 'active')
                ->groupBy('cl.named, cl.numeral, ss.sName')
                ->orderBy('cl.numeral', 'ASC')
                ->orderBy('ss.sName', 'ASC')
                ->findAll();

            $enrollmentLabels = array_column($enrollment, 'stream');
            $enrollmentValues = array_column($enrollment, 'total');

            // Performance — average marks per exam
            $performance = $this->rawResultModel
                ->select('exName as exam, AVG(raScore) as avg_score, exCreated')
                ->join('exams', 'exams.exID = rawresults.exam_id')
                ->groupBy('exam_id')
                ->orderBy('exCreated', 'DESC')
                ->limit(6)
                ->findAll();

            $performanceLabels = array_column($performance, 'exam');
            $performanceValues = array_map('floatval', array_column($performance, 'avg_score'));

            return view('admin/dashboard', [
                'students' => $students,
                'employees' => $employees,
                'subjects' => $subjects,
                'exams' => $exams,
                'pageTitle' => 'Dashboard',

                // Send chart data
                'enrollmentLabels' => json_encode($enrollmentLabels),
                'enrollmentValues' => json_encode($enrollmentValues),
                'performanceLabels' => json_encode($performanceLabels),
                'performanceValues' => json_encode($performanceValues),
            ]);
        } //End of dashboard

        //Download Employee excel Template
        public function downloadEmployeeTemplate(){
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Header row: now using class + stream names
            $sheet->setCellValue('A1', 'First Name');
            $sheet->setCellValue('B1', 'Middle Name');
            $sheet->setCellValue('C1', 'Surname');
            $sheet->setCellValue('D1', 'Sex');
            $sheet->setCellValue('E1', 'Dob');
            $sheet->setCellValue('F1', 'Email');
            $sheet->setCellValue('G1', 'Hired Date');
            $sheet->setCellValue('I1', 'Is admin');
            $sheet->setCellValue('H1', 'Is Head');
            $sheet->setCellValue('J1', 'Is Academic');
            $sheet->setCellValue('K1', 'Is Teacher');

            $writer = new Xlsx($spreadsheet);
            $filename = 'employee_hering_template.xlsx';

            // Output file
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            $writer->save('php://output');
            exit;
        }
        //Receive information 
        protected function sendEmployeeCredentials($toEmail, $toName, $plainPassword){
            $email = \Config\Services::email();
            $email->setTo($toEmail);
            $email->setFrom('ashirukattoo@gmail.com', 'LYAMAHORO SEC SCHOOL');
            $email->setSubject('Your System Login Credentials');

            $message = "
                <p>Hello <strong>{$toName}</strong>,</p>
                <p>Your account has been created in the system.</p>
                <p><strong>Login details:</strong></p>
                <ul>
                    <li>Email: {$toEmail}</li>
                    <li>Password: {$plainPassword}</li>
                </ul>
                <p>Please log in and change your password immediately.</p>
                <p>Regards,<br>LMS Team</p>
            ";

            $email->setMessage($message);

            return $email->send();
        }
        protected function sendEmployeeResetLink($toEmail, $toName, $resetLink){
            $email = \Config\Services::email();

            $email->setTo($toEmail);
            $email->setFrom('ashirukattoo@gmail.com', 'CI4 LMS');
            $email->setSubject('Set Your System Password');

            $message = "
                <p>Hello <strong>{$toName}</strong>,</p>
                <p>Your account has been created. Please set your password by clicking the link below:</p>
                <p><a href='{$resetLink}'>Set Your Password</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>Regards,<br>CI4 LMS Team</p>
            ";

            $email->setMessage($message);
            return $email->send();
        }
        public function resetPassword($token){
            $record = $this->db->table('password_resets')
                ->where('token', $token)
                ->where('expires_at >=', date('Y-m-d H:i:s'))
                ->get()
                ->getRow();

            if (!$record) {
                return redirect()->to('/login')->with('error', 'Invalid or expired link.');
            }

            if ($this->request->getMethod() === 'post') {
                $password = $this->request->getPost('password');
                $this->employeeModel->update($record->employee_id, [
                    'empPassword'    => password_hash($password, PASSWORD_DEFAULT),
                    'empForceReset'  => 0
                ]);

                // Delete token after use
                $this->db->table('password_resets')->where('id', $record->id)->delete();

                return redirect()->to('/login')->with('success', 'Password set successfully.');
            }

            return view('auth/reset_password', ['token' => $token]);
        }
        //Import bulk employee via cvs or xlxs
        public function importEmployees(){
            $file = $this->request->getFile('file');
            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'Invalid file uploaded.');
            }
            $ext  = strtolower($file->getClientExtension());
            $path = $file->getTempName();
            $rows = ($ext === 'csv')
                ? $this->parseCSV($path)
                : $this->parseXLSX($path);
            $inserted   = 0;
            $duplicates = [];
            foreach ($rows as $row) {
                // Skip empty rows
                if (empty($row['First Name']) || empty($row['Email'])) {
                    continue;
                }
                // Duplicate check (unique email)
                $exists = $this->employeeModel
                    ->where('empEmail', trim($row['Email']))
                    ->first();
                if ($exists) {
                    $duplicates[] = $row['Email'];
                    continue;
                }
                // Generate password
                $plainPassword = '12345@lms';

                // Insert employee
                $this->employeeModel->insert([
                    'empFname'     => trim($row['First Name']),
                    'empMname'     => trim($row['Middle Name'] ?? null),
                    'empSurname'   => trim($row['Surname']),
                    'empSex'       => trim($row['Sex']),
                    'empDob'       => $row['Dob'] ?? null,
                    'empRole'      => 'Teacher', // default ENUM
                    'empEmail'     => trim($row['Email']),
                    'empPassword'  => password_hash($plainPassword, PASSWORD_DEFAULT),
                    'empHired'     => $row['Hired Date'] ?? null,
                    'empRegisterd' => date('Y-m-d H:i:s'),
                    'empStatus'    => 'Active',
                ]);
                $employeeId = $this->employeeModel->getInsertID();

                // Assign main role
                if (!empty($row['Role'])) {
                    $this->employeeRoleModel->insert([
                        'employee_id' => $employeeId,
                        'erRole'      => $row['Role'],
                        'erAssigned'  => date('Y-m-d'),
                        'erCreated'   => date('Y-m-d H:i:s'),
                        'erStatus'    => 'Active'
                    ]);
                }
                // Assign Admin role if flagged
                if (!empty($row['Is Admin'])) {
                    $this->employeeRoleModel->insert([
                        'employee_id' => $employeeId,
                        'erRole'      => 'Admin',
                        'erAssigned'  => date('Y-m-d'),
                        'erCreated'   => date('Y-m-d H:i:s'),
                        'erStatus'    => 'Active'
                    ]);
                }
                // After inserting employee
                $employeeId = $this->employeeModel->getInsertID();

                // generate reset token
                $token = bin2hex(random_bytes(32));
                $expires = Time::now()->addHours(1)->toDateTimeString();

                // insert token into DB
                $db = \Config\Database::connect();
                $db->table('password_resets')->insert([
                    'employee_id' => $employeeId,
                    'token'       => $token,
                    'expires_at'  => $expires,
                ]);

                // send email with reset link
                $resetLink = base_url("auth/reset-password/{$token}");
                $this->sendEmployeeResetLink(
                    $row['Email'], 
                    $row['First Name'].' '.$row['Surname'], 
                    $resetLink
                );

                $inserted++;
            }
            $msg = "Imported {$inserted} employees successfully.";
            if (!empty($duplicates)) {
                $msg .= " Duplicate emails skipped: " . implode(', ', $duplicates);
            }

            return redirect()->back()->with('success', $msg);
        }
        //Add Employee in DB
        public function add_employee(){
            $data = [
                'grades' => $this->salaryGradeModel->findAll(),
                'pageTitle' => 'Add New Employee',
            ];
            return view('admin/staff/create', $data);
        }
        public function save_employee(){
            $role = $this->request->getPost('role');
            $position = $this->request->getPost('position');
            $sex = $this->request->getPost('sex');
            if (strtolower($position) === 'head' &&  strtolower($sex) === 'female' ) {
                $position = 'Headmistress';
            }
            $phone = $this->request->getPost('phone');
            $data = [
                'empFname' => ucwords(strtolower($this->request->getPost('fname'))),
                'empMname' => ucwords(strtolower($this->request->getPost('mname'))),
                'empSurname' => ucwords(strtolower($this->request->getPost('lname'))),
                'empSex' => ucwords(strtolower($this->request->getPost('sex'))),
                'empDob' => $this->request->getPost('dob'),
                'empRole' => $role,
                'empPosition' => $this->request->getPost('position'),
                'empPhone' => $phone,
                'empEmail' => $this->request->getPost('email'),
                'empPassword' => password_hash(
                    $this->request->getPost('password'),
                    PASSWORD_DEFAULT
                ),
                'empSalaryGradeID' => $this->request->getPost('empSalaryGradeID'),
                'empHired' => $this->request->getPost('hired'),
                'empRegisterd' => date('Y-m-d H:i:s'),
                'empStatus' => 'active',
            ];

            $this->employeeModel->insert($data);

            $name = ucwords(
                strtolower(
                    $this->request->getPost('fname') . ' ' .
                    $this->request->getPost('lname')
                )
            );
            $passkey = $this->request->getPost('password');
            $email = $this->request->getPost('email');

            //SEND SMS
            if ($this->smsService->checkPhoneValidity($phone)) {
                $message = "Karibu LSS. Ndugu {$name}, ".
                    "akaunti yako imefunguliwa kama {$role}. ".
                    "Tunafurahi kuwa sehemu ya timu yetu.\nBarua pepe ni '{$email}', emailNywira yako ni {$passkey}.";

                $this->smsService->queueSMS([
                    'phone' => $phone,
                    'message' => $message,
                    'status' => 'Pending',
                    'retries' => 0
                ]);
            }

            return redirect()
                    ->back()
                    ->with(
                        'success',
                        'Account of '.$name.' Created Successfully!'
                    );
        } //End of attempt Register
        public function view_employee(){
            $data  = [
                'pageTitle' => 'Employees',
                'employees' => $this->employeeModel->activeEmployee(),
            ];
            return view('admin/staff/view', $data);
        }
        public function erase_employee($id){
            if($this->employeeModel->del($id)){
                return redirect()->back()->with('success', 'Permanent Deleted');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong|');
            }
        }
        public function edit_employee($id){
            $data = [
                'grades' => $this->salaryGradeModel->findAll(),
                'emp' => $this->employeeModel->getEmp($id),
                'roles' =>$this->employeeModel->getRoles($id),
                'pageTitle' => 'Update Employee\'s Info'
            ];
            return view('admin/staff/edit', $data);
        }
        public function updateEmployee(){
            $empID = $this->request->getPost('empID');

            if (!$empID) {
                return redirect()->back()->with('error', 'Invalid Employee ID.');
            }

            $data = [
                'empFname' => $this->request->getPost('empFname'),
                'empMname' => $this->request->getPost('empMname'),
                'empSurname' => $this->request->getPost('empSurname'),
                'empEmail' => $this->request->getPost('empEmail'),
                'empSex' => $this->request->getPost('empSex'),
                'empDob' => $this->request->getPost('empDob'),
                'empHired' => $this->request->getPost('empHired'),
                'empStatus' => $this->request->getPost('empStatus'),
                'empSalaryGradeID' => $this->request->getPost('empSalaryGradeID'),
            ];

            // Remove null values (optional but clean)
            $data = array_filter($data, function($value) {
                return $value !== null;
            });

            if ($this->employeeModel->update($empID, $data)) {
                return redirect()->to('admin/manage/employees')->with('success', 'Employee updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update employee.');
            }
        }

        public function about_employee($id){
            $data = [
                'emp'       =>   $this->employeeModel->getEmp($id),
                'roles'     =>   $this->empRoles->getMyRoles($id, 'all'),
                'pageTitle' =>   'More Employee\'s Info'
            ];
            return view('admin/staff/employee', $data);
        }
        public function manageEmp(){
            $data  = [
                'pageTitle' =>'Manage Employees\' Record',
                'employees' => $this->employeeModel->activeEmployee(),
            ];
            return view('admin/staff/manage', $data);
        }
        public function assign_subjectsToEmployee(){
            if (isset($_POST['assigneTeacher'])) {
                $num = $this->teachesModel->countAllResults();
                $num +=1;
                if ($num < 10) { $num = '00'.$num;   }
                elseif($num <100){$num = '0'.$num; }
                $subject = $this->request->getPost('subject');
                $stream = $this->request->getPost('stream');
                $stream = $this->sbStreamModel->get_Id($stream, $subject);
                foreach ($stream as $key) {
                    $stream = $key['id'];
                    if(!isset($stream) || !is_numeric($stream)) return redirect()->back()->with('error', 'Subject not offered in this stream');
                }
                $take = [
                    'employee'   => $this->request->getPost('employee'),
                    'stream'     => $stream,
                    'assigndate' => $this->request->getPost('assigndate'),
                    'todate'     => $this->request->getPost('todate'),
                    'id'         => $num   
                ];
                $data = [
                    'code'         => 'AC'.$num,
                    'employee_id'  => $this->request->getPost('employee'),
                    'assigneDate' => $this->request->getPost('assigndate'),
                    'todate'     => $this->request->getPost('todate'),
                    'stream_id'  => $stream,
                    'created'    =>date('Y-m-d H:i:s')
                ];
                $query = $this->teachesModel->addTeacher($data);
                //$query = $this->teachesModel->insert($data);
                if ($query) {
                    return redirect()->back()->with('success', 'Saved Successfully!');
                }else{
                    return redirect()->back()->with('error', 'Something went Wrong');
                }
            }else{
                $data = [
                    'subjects'  => $this->subjectModel->findAll(),
                    'streams'   => $this->streamModel->getAllClasses(),
                    'teachers'  => $this->employeeModel->activeTeacher(),
                    'details'   => $this->sbStreamModel->get_details(),
                    'workers'   => $this->teachesModel->addTeacher(),
                    'pageTitle' => 'Assign Subjects to Teacher',
                ];
                return view('admin/subjects/assign_teacher', $data);
            }  
        }

    /* =====================================================================================
            STUDENTS MODULE
    =======================================================================================*/
        //Export students Report 
        public function export_students_list($class_id = null){
            // Fetch students in class
            $students = $this->studentModel->getByStatus($class_id);
            if(empty($students)){
                return redirect()->back()->with('error','No students found for this class.');
            }
            // Create Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Student_list');

            // Header row
            $col = 'A';
            $sheet->setCellValue($col++.'1','Reg. No.');
            $sheet->setCellValue($col++.'1','First Name');
            $sheet->setCellValue($col++.'1','Middle Name');
            $sheet->setCellValue($col++.'1','Surname');
            $sheet->setCellValue($col++.'1','Sex');
            $sheet->setCellValue($col++.'1','Class');

            // Fill students
            $row = 2;
            foreach($students as $stu){
                $col = 'A';
                $sheet->setCellValue($col++.$row, $stu['id']);
                $sheet->setCellValue($col++.$row, strtoupper($stu['fname']));
                $sheet->setCellValue($col++.$row, strtoupper($stu['mname']));
                $sheet->setCellValue($col++.$row, strtoupper($stu['surname']));
                $sheet->setCellValue($col++.$row, strtoupper($stu['sex']));
                $sheet->setCellValue($col++.$row, strtoupper($stu['class']));
                $row++;
            }

            // Column widths
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(8);
            $sheet->getColumnDimension('F')->setWidth(15);

            // Download
            $writer = new Xlsx($spreadsheet);
            $filename = 'Active Students Registered_'.$class_id.'.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        }
        //Get Student's Name
        protected function getStudentName($id=0){
            foreach ($this->studentModel->getFullName($id) as $row) {
                $name = $row['name'];
            }
            return $name;
        }
        //Add student Form
        public function create_student(){
            $data['guardians'] = $this->guardianModel->findAll();
            $data['streams'] = $this->streamModel->getAllClasses();
            $data['pageTitle'] = 'Add Students';
            return view('admin/students/create', $data);
        }
        //Save Single Data in 
        public function store_student(){
            $data = [
                'stuFname' => $this->request->getPost('fname'),
                'stuMname' => $this->request->getPost('mname'),
                'stuSurname' => $this->request->getPost('lname'),
                'stuSex' => $this->request->getPost('sex'),
                'stuDob' => $this->request->getPost('dob'),
                'stream_id' => $this->request->getPost('class'),
                'guardian_id' => $this->request->getPost('guardian_id')
            ];
            $result = $this->studentModel->insertStudent($data);
            if(isset($result['error'])){
                return redirect()->back()->with('error', $result['error']);
            }
            $name = $this->request->getPost('fname').' '.$this->request->getPost('lname');
            return redirect()->to('/admin/add/students')->with('success', $name.' Registered Successfully');
        }
        //Download student excel Template
        public function downloadStudentTemplate(){
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header row: now using class + stream names
            $sheet->setCellValue('A1', 'indexNo');
            $sheet->setCellValue('B1', 'stuFname');
            $sheet->setCellValue('C1', 'stuMname');
            $sheet->setCellValue('D1', 'stuSurname');
            $sheet->setCellValue('E1', 'stuSex');
            $sheet->setCellValue('F1', 'stuDob');
            $sheet->setCellValue('G1', 'ClassName');
            $sheet->setCellValue('H1', 'StreamName');
            $sheet->setCellValue('I1', 'guardian_id'); // optional

            $writer = new Xlsx($spreadsheet);
            $filename = 'students_admission_template.xlsx';

            // Output file
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            $writer->save('php://output');
            exit;
        }
        //Detect students 
        protected function isStudentDuplicate($data){
            return $this->studentModel
                        ->where('stuFname', $data['stuFname'])
                        ->where('stuSurname', $data['stuSurname'])
                        ->where('stuDob', $data['stuDob'])
                        ->countAllResults() > 0;
        }
        //Process the CVS file
        protected function processCSV($path) {
            $handle = fopen($path,"r");
            $header = fgetcsv($handle, 1000, ",");
            while(($row = fgetcsv($handle, 1000, ",")) !== FALSE){
                $data = array_combine($header, $row);
                $this->saveRow($data);
            }
            fclose($handle);
        }
        //Process the XLSV file
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
        //Record single Row proccessed
        protected function saveRow($data, $type = 'student'){
            if ($type === 'student') {

                // DUPLICATE CHECK
                if ($this->isStudentDuplicate($data)) {
                    log_message('error', 'Duplicate student skipped: ' . json_encode($data));
                    return; // skip
                }
                if (empty($data['indexNo'])) {
                   $index = null;
                }else{
                    $index = $data['indexNo'];
                }
                // Save new student
                $this->studentModel->insert([
                    'indexNo'    => $index,
                    'stuFname'    => $data['stuFname'],
                    'stuMname'    => $data['stuMname'],
                    'stuSurname'  => $data['stuSurname'],
                    'stuSex'      => $data['stuSex'],
                    'stuDob'      => $data['stuDob'],
                    'stream_id'   => $data['class_id'] ?? null,
                    'guardian_id' => $data['guardian_id'] ?? null,
                    'stuStatus'   => 'active'
                ]);

                return;
            }

            // Employees
            if ($type === 'employee') {
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
                return;
            }

            // Guardians
            if ($type === 'guardian') {
                $this->guardianModel->insert([
                    'empname'=>$data['empname'],
                    'guSex'=>$data['guSex'],
                    'empDob'=>$data['empDob'],
                    'guOccupasion'=>$data['guOccupasion'],
                    'empEmail'=>$data['empEmail'],
                    'empPassword'=>password_hash($data['empPassword'], PASSWORD_DEFAULT),
                    'guRegisterd'=>date('Y-m-d'),
                    'guStatus'=>'active'
                ]);
                return;
            }
        }
        //Receive information 
        public function importStudents(){
            ini_set('max_execution_time', 300);
            $file = $this->request->getFile('file');
            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'Invalid file uploaded.');
            }

            $ext = strtolower($file->getClientExtension());
            $path = $file->getTempName();

            $rows = ($ext === 'csv') ? $this->parseCSV($path) : $this->parseXLSX($path);

            $inserted = 0;
            $duplicates = [];
            $notfound = [];

            foreach ($rows as $row) {
                if (!isset($row['stuFname']) || trim($row['stuFname']) === '') continue;

                // Duplicate detection by full name
                $exists = $this->studentModel
                    ->where('stuFname', ucwords(strtolower($row['stuFname'])))
                    ->where('stuMname', ucwords(strtolower($row['stuMname'])))
                    ->where('stuSurname', ucwords(strtolower($row['stuSurname'])))
                    ->first();
                if ($exists) {
                    $duplicates[] = $row['stuFname'] . ' ' . $row['stuSurname'];
                    continue;
                }

                // Find stream_id from ClassName + StreamName
                $stream = $this->streamModel->db->table('streams st')
                    ->select('st.sid')
                    ->join('classes cl', 'st.class_id = cl.cid')
                    ->where('cl.named', $row['ClassName'])
                    ->where('st.sName', $row['StreamName'])
                    ->get()
                    ->getRowArray();

                if (!$stream) {
                    $notfound[] = $row['stuFname'] . ' ' . $row['stuSurname'] . " (Class/Stream not found)";
                    continue;
                }
                if (empty($row['indexNo'])) {
                    $index = null;
                }else{
                    $index = ucwords(strtolower($row['indexNo']));
                }
                // Insert student
                $this->studentModel->insert([
                    'indexNo'     => $index,
                    'stuFname'    => ucwords(strtolower($row['stuFname'])),
                    'stuMname'    => ucwords(strtolower($row['stuMname'])),
                    'stuSurname'  => ucwords(strtolower($row['stuSurname'])),
                    'stuSex'      => ucwords(strtolower($row['stuSex'])),
                    'stuDob'      => $row['stuDob'],
                    'stream_id'   => $stream['sid'],
                    'guardian_id' => $row['guardian_id'] ?? null,
                    'stuStatus'   => 'Active'
                ]);

                $inserted++;
            }

            $msg = "Imported: {$inserted} students.";
            if ($duplicates) $msg .= " Duplicates skipped: " . implode(', ', $duplicates);
            if ($notfound)  $msg .= " Class/Stream not found: " . implode(', ', $notfound);

            return redirect()->back()->with('success', $msg);
        }
        // ------------------------------------------------------------
        // PARSE CSV 
        // ------------------------------------------------------------
        protected function parseCSV($path){
            $handle = fopen($path, "r");
            $header = fgetcsv($handle, 1000, ",");
            $rows = [];
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $rows[] = array_combine($header, $row);
            }
            fclose($handle);
            return $rows;
        }
        // ------------------------------------------------------------
        private function parseXLSX($path)        {
            $sheet = IOFactory::load($path)->getActiveSheet()->toArray(null, true, true, true);
            $header = array_shift($sheet);
            $rows = [];
            foreach ($sheet as $line) {
                $row = [];
                foreach ($header as $col => $key) {
                    $row[$key] = $line[$col];
                }
                $rows[] = $row;
            }
            return $rows;
        }
        //Store Bulk Records in Database
        public function import(){
            $file = $this->request->getFile('file');

            if(!$file->isValid()){
                return redirect()->back()->with('error','File upload failed');
            }
            $ext = $file->getClientExtension();
            if($ext === 'csv' || $ext === 'txt'){
                $this->processCSV($file->getTempName());
            } else if(in_array($ext, ['xlsx','xls'])){
                $this->processXLSX($file->getTempName());
            } else {
                return redirect()->back()->with('error','Invalid file type');
            }
            return redirect()->back()->with('success','Data on File Uploaded successfully!');
        }
        //Render Students records
        public function view_students(){
            $data['students'] = $this->studentModel->getFullDetails();
            return view('admin/students/index', $data);
        }
        //Render Studends Record Management UI
        public function manage_students(){
            $data=['students' => $this->studentModel->getFullDetails(1),
                   'pageTitle'=>'Manage Students Records'
            ];
            return view('admin/students/manage', $data);
        }
        public function erase_student($id){
            if($this->studentModel->del($id)){
                return redirect()->back()->with('success', 'Permanent Deleted');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong|');
            }
        }
        //Manage the student Records
        public function manage_student(){
            if (isset($_GET['student'])) {
                //Update Students flag him/her status as Deleted
                $data['student'] = $this->studentModel->update($_GET['student'],
                    ['stuStatus'=>'Deleted']);
                return redirect()->back()->with('success', 'Deleted successfully');
            }elseif (isset($_GET['edit'])) {
                $data['student'] = $this->studentModel->getRecord($_GET['edit']);
                $data['streams'] = $this->streamModel->getAllClasses();
                $data['guardians'] = $this->guardianModel->findAll();
                $data['pageTitle'] = 'Update Students Records';
                return view('admin/students/edit', $data);
            }elseif(isset($_GET['transfer'])){
                //Update Students flag him/her status as Transfered
                $data['student'] = $this->studentModel->update($_GET['transfer'],
                    ['stuStatus'=>'Transfered']);
                return redirect()->back()->with('success', 'Transfered successfully');
            }elseif(isset($_GET['unblock'])){
                //Update Students flag him/her status as Transfered
                $data['student'] = $this->studentModel->update($_GET['unblock'],
                    ['stuStatus'=>'Active']);
                return redirect()->back()->with('success', 'Activated successfully');
            }else{
                $data['students'] = $this->studentModel->getFullDetails();
                return view('admin/students/manage', $data);
            }
        }
        //Update the Students Details
        public function update_student(){
            $this->studentModel->update($_POST['id'], [
                'stuFname' => $this->request->getPost('fname'),
                'stuMname' => $this->request->getPost('mname'),
                'stuSurname' => $this->request->getPost('lname'),
                'stuSex' => $this->request->getPost('sex'),
                'stuDob' => $this->request->getPost('dob'),
                'stream_id' => $this->request->getPost('class'),
                'guardian_id' => $this->request->getPost('guardian') ?? null
            ]);
            return redirect()->back()->with('success', 'Info Updated Successfully');
        }
        public function save_guardian() {
            $request = $this->request;
            $student_id = $request->getPost('student_id');
            $use_existing = $request->getPost('use_existing');

            if(!$student_id) {
                return $this->response->setJSON(['status'=>'error','message'=>'Invalid student ID']);
            }

            if($use_existing == 1){
                $guardian_id = $request->getPost('existing_guardian_id');
                if(!$guardian_id || !$this->guardianModel->find($guardian_id)){
                    return $this->response->setJSON(['status'=>'error','message'=>'Please select a valid guardian']);
                }
                $this->studentModel->update($student_id, ['guardian_id' => $guardian_id]);
                $guardian = $this->guardianModel->find($guardian_id);
                return $this->response->setJSON([
                    'status'=>'success',
                    'message'=>'Guardian assigned successfully',
                    'guardian'=>$guardian
                ]);
            } else {
                // Create new guardian
                $data = [
                    'guName' => $request->getPost('guardian_name'),
                    'guSex' => $request->getPost('sex'),
                    'guOccupasion' => $request->getPost('occupation'),
                    'guEmail' => $request->getPost('email'),
                    'guRegisterd' => date('Y-m-d'),
                    'guStatus'=>'active'
                ];

                $guardian_id = $this->guardianModel->insert($data, true); // true = return ID
                if(!$guardian_id){
                    return $this->response->setJSON(['status'=>'error','message'=>'Failed to create guardian']);
                }

                $this->studentModel->update($student_id, ['guardian_id' => $guardian_id]);
                $guardian = $this->guardianModel->find($guardian_id);

                return $this->response->setJSON([
                    'status'=>'success',
                    'message'=>'New guardian created and assigned',
                    'guardian'=>$guardian
                ]);
            }
        }
        public function getAllGurdian() {
            $data = $this->guardianModel->findAll();
            return $this->response->setJSON($data);
        }
        public function promoteStream($sid){
            service('studentPromotion')->promoteStream($sid);
            return redirect()->back()->with('success', 'Students promoted successfully');
        }
        public function promoteOLevel(){
            $ay = service('academicCalendar')->currentAY();
            service('studentPromotion')->promoteLevel('O-level', $ay);
            return redirect()->back()->with('success', 'O-Level students promoted successfully');
        }

    /*===============================================================================================
            SCHOOL MODULE
    =================================================================================================*/
        public function getTermsByYear() {
            $ayID = $this->request->getPost('ayID');

            if (!$ayID) {
                return $this->response->setJSON([]);
            }

            $termModel = new \App\Models\TermModel();

            $terms = $termModel
                        ->where('ay_id', $ayID)
                        ->findAll();

            return $this->response->setJSON($terms);
        }
        public function save_academicyear(){
            $data = [
                'year' => $this->request->getPost('name'),
                'level' => $this->request->getPost('level'),
                'start' => $this->request->getPost('start'),
                'end' => $this->request->getPost('end'),
            ];
            $save = $this->ayModel->saveAy($data);
            if ($save) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => $this->request->getPost('name').' added Successfully!'
                ]);
            }else{
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $this->request->getPost('name').' didn\'t Saved!'
                ]);
            }
        }
        public function add_stream(){
            $data = [
                'streams'   => $this->streamModel->getAllClasses(),
                'classes'   => $this->classModel->findAll(),
                'pageTitle' => 'Add Stream',
            ];
            return view('admin/liabilities/add_stream', $data);
        }
        public function store_stream(){
            $data = [
                'class' => $this->request->getPost('class'),
                'name' => $this->request->getPost('stream'),
                'status' => 'Active'
            ];
            $result = $this->streamModel->insertStream($data);
            if(!$result){
                return redirect()->back()->with('error', 'Same Stream Already Exist');
            }
            return redirect()->back()->with('success', ' Added Successfully');
        }
        public function erase_stream($id){
            if($this->streamModel->del($id)){
                return redirect()->back()->with('success', 'Permanent Deleted');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong|');
            }
        }
        public function streams(){
            $data = [
                'streams'   => $this->streamModel->getAllClasses(),
                'classes'   => $this->classModel->findAll(),
                'pageTitle' => 'Recorded Streams',
            ];
            return view('admin/liabilities/streams', $data);
        }
        public function activate_stream($id){
            if($this->streamModel->updateStatus($id, 'Active')):
                return redirect()->back()->with('success', "Activated successfully");
            else:
                return redirect()->back()->with('error', 'Something Went Wrong');
            endif;
        }
        public function deactivate_stream($id){
            if($this->streamModel->updateStatus($id, 'Inactive')):
                return redirect()->back()->with('success', "Deactivated successfully");
            else:
                return redirect()->back()->with('error', 'Something Went Wrong');
            endif;
        }
        public function assign_stream_master(){
            if (isset($_POST['assignClassMaster'])) {
                $num = $this->classMasterModel->countAllResults();
                $num +=1;
                if ($num < 10) { $num = '00'.$num;   }
                elseif($num <100){$num = '0'.$num; }
                $subject = $this->request->getPost('subject');
                $stream = $this->request->getPost('stream');
                $todate = $this->request->getPost('todate');
                if ($todate == null || $todate == '0000-00-00') {
                    $todate = null;
                }
                $eid = $this->request->getPost('employee');
                $assdate = $this->request->getPost('assigndate');
                $take = [
                    'employee'   => $eid,
                    'stream'     => $stream,
                    'assigndate' => $assdate,
                    'todate'     => $todate,
                    'id'         => $num   
                ];
                $query = $this->classMasterModel->addTeacher($take);
                if ($query) {
                    if(!$this->empRoles->isCM($eid)){
                        $data = [
                            'id' =>$eid,
                            'role' => 'Class Master',
                            'assigned' =>$assdate
                        ];
                        if($this->empRoles->add($data)){
                            return redirect()->back()->with('success', 'Saved Successfully!');                            
                        }else{
                            return redirect()->back()->with('error', 'Added Successfully, But Failed to Update Employee\'s Roles');
                        }
                    }
                }else{
                    return redirect()->back()->with('error', 'something went Wrong');
                }
            }else{
                $data = [
                    'subjects'  => $this->subjectModel->findAll(),
                    'streams'   => $this->streamModel->getAllClasses(),
                    'teachers'  => $this->employeeModel->activeTeacher(),
                    'details'   => $this->sbStreamModel->get_details(),
                    'workers'   => $this->classMasterModel->addTeacher(),
                    'pageTitle' => 'Assign Class Teacher',
                ];
                return view('admin/subjects/assign_stream_teacher', $data);
            }  
        }
        public function update_assign_stream_master($id, $status){
            if ($status == 'Canceled') {
               if ($this->classMasterModel->updateStatus($id, $status)) {
                  return redirect()->back()->with('success', $status.' Successfully');
              }else{
                return redirect()->back()->with('Error', 'Failed to Update');
              } 
            }elseif ($status == 'Activate') {
               if ($this->classMasterModel->updateStatus($id, $status)) {
                  return redirect()->back()->with('success', $status.' Successfully');
              }else{
                return redirect()->back()->with('Error', 'Failed to Update');
              } 
            }elseif($status == 'Old'){
                if ($this->classMasterModel
                       ->update($id, ['cmStatus' => 'Old', 'cmTodate'=>date('Y-m-d')])){
                  return redirect()->back()->with('success', 'Seced Successfully');
              }else{
                return redirect()->back()->with('Error', 'Failed to Update');
              }
            }else{
                if ($this->classMasterModel
                      ->update($id, ['cmStatus' => $status, 'cmTodate'=>date('Y-m-d')])){
                  return redirect()->back()->with('success', 'Updated Successfully');
                }else{
                    return redirect()->back()->with('Error', 'Failed to Update');
                }
            }       
        }
        public function add_class(){
            $data = [
                'streams'   => $this->streamModel->getAllClasses(),
                'classes'   => $this->classModel->findAll(),
                'pageTitle' => 'Add Class',
            ];
            return view('admin/liabilities/add_class', $data);
        }
        public function save_class(){
            $data = [
                        'named'=> $this->request->getPost('class'),
                        'short' => $this->request->getPost('short'),
                        'numeral'=> $this->request->getPost('numeral'),
                        'level'=> $this->request->getPost('level'),
                        'created' =>date('Y-m-d H:i:s'),
                        'updated' =>date('Y-m-d H:i:s'),
                        'status' =>'Active',
                    ];
            if ($this->classModel->insert($data)) {
                return redirect()->back()->with('success', $this->request->getPost('class').'  Recorded successfully');
            }else{
                return redirect()->back()->with('error', $this->request->getPost('class').' Didn\'t Recorded');
            }
        }
        public function classes(){
            $data = [
                'classes'   => $this->classModel->findAll(),
                'pageTitle' => 'Recorded Classes',
            ];
            return view('admin/liabilities/classes', $data);
        }
    /*================================================================================================
            SUBJECT MODULE
    =================================================================================================*/
        public function add_Subject(){
            return view('admin/subjects/add', ['pageTitle' =>'Add subjects']);
        }
        public function store_subject(){
            $data = [
                'name' =>$this->request->getPost('name'),
                'category' =>$this->request->getPost('category'),
                'code'=>$this->request->getPost('code'),
                'short'=>$this->request->getPost('short'),
                'level'=>$this->request->getPost('level'),
                'curriculum' =>$this->request->getPost('curriculum'),
                'created' => date('Y-m-d H:i:s')
            ];

            if ($this->subjectModel->add_subject($data)) {
                return redirect()->back()->with('success', $this->request->getPost('name').' Added Successfully. <a class="btn btn btn-success" href="/admin/view/subjects">Subjects Recorded</a>');
            }else{
                return redirect()->back()->with('error', $this->request->getPost('name').' Didn\'t Recorded');
            }
        }
        public function subjects(){
            $data = [
                'subjects'   => $this->subjectModel->findAll(),
                'pageTitle' => 'Recorded Subjects',
            ];
            return view('admin/subjects/index', $data);
        }
        public function downloadSubjectTemplate(){
            // Load PhpSpreadsheet classes
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set header row
            $sheet->setCellValue('A1', 'Name');
            $sheet->setCellValue('B1', 'Code');
            $sheet->setCellValue('C1', 'Short');
            $sheet->setCellValue('D1', 'Category');
            $sheet->setCellValue('E1', 'Level');
            $sheet->setCellValue('F1', 'Curriculum');

            // Add sample/example row to show format
            $sheet->setCellValue('A2', 'Geography');
            $sheet->setCellValue('B2', '013');
            $sheet->setCellValue('C2', 'Geog');
            $sheet->setCellValue('D2', 'Core');
            $sheet->setCellValue('E2', 'O-Level');
            $sheet->setCellValue('F2', 'New');

            // Style header row
            $sheet->getStyle('A1:F1')->getFont()->setBold(true);

            // Autofit columns
            foreach (range('A','F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Download settings
            $filename = 'subjects_template.xlsx';
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            // Output file to browser
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        }
        public function importSubjects(){
            $file = $this->request->getFile('file');
            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'Invalid file uploaded.');
            }
            $ext = $file->getClientExtension();
            if (!in_array($ext, ['csv', 'xlsx'])) {
                return redirect()->back()->with('error', 'Only CSV or XLSX files are allowed.');
            }
            // Load file using PhpSpreadsheet
            if ($ext == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            try {
                $spreadsheet = $reader->load($file->getTempName());
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'File could not be read. Ensure it is a valid CSV/XLSX file.');
            }
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            if (count($rows) < 2) {
                return redirect()->back()->with('error', 'The file is empty. Please use the provided template.');
            }
            $subjectModel = new \App\Models\SubjectModel();
            $imported = 0;
            $duplicateInFile = [];
            $duplicateInDB = [];
            $missingFields = [];
            $seenNames = [];
            // Skip header row
            foreach (array_slice($rows, 1) as $index => $row) {
                $rowNumber = $index + 2; // Excel row number
                // Row columns
                $name       = trim($row[0] ?? '');
                $code       = trim($row[1] ?? '');
                $short      = trim($row[2] ?? '');
                $category   = trim($row[3] ?? '');
                $level      = trim($row[4] ?? '');
                $curriculum = trim($row[5] ?? '');
                // Ignore completely empty rows
                if ($name == "") {
                    continue;
                }
                // Validate required fields
                if ($name == "" || $category == "" || $level == "" || $curriculum == "") {
                    $missingFields[] = "Row $rowNumber: Missing required fields.";
                    continue;
                }
                // File-level duplicate detection
                if (isset($seenNames[strtolower($name)])) {
                    $duplicateInFile[] = "Row $rowNumber: Duplicate subject '$name' already exists in the file.";
                    continue;
                }
                $seenNames[strtolower($name)] = true;
                // Database-level duplicate detection
                $exists = $subjectModel->where('subName', $name)->first();
                if ($exists) {
                    $duplicateInDB[] = "Row $rowNumber: '$name' already exists in the system.";
                    continue;
                }
                // All validations passed → Insert
                $data = [
                    'subName'       => $name,
                    'subCode'       => $code,
                    'subShort'      => $short,
                    'subCategory'   => $category,
                    'subLevel'      => $level,
                    'subCurriculum' => $curriculum,
                    'subCreated'    => date('Y-m-d H:i:s'),
                ];
                $subjectModel->insert($data);
                $imported++;
            }
            // Build feedback messages
            $message = "$imported subjects imported successfully.";
            if (!empty($duplicateInFile)) {
                $message .= "<br><b>Duplicates within file:</b><br>" . implode("<br>", $duplicateInFile);
            }
            if (!empty($duplicateInDB)) {
                $message .= "<br><b>Duplicates already in system:</b><br>" . implode("<br>", $duplicateInDB);
            }
            if (!empty($missingFields)) {
                $message .= "<br><b>Rows missing required fields:</b><br>" . implode("<br>", $missingFields);
            }
            // Display results
            if ($imported > 0) {
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->back()->with('error', $message);
            }
        }

        public function erase_subject($id){
            if($this->subjectModel->del($id)){
                return redirect()->back()->with('success', 'Permanent Deleted');
            }else{
                return redirect()->back()->with('error', 'Something Went Wrong|');
            }
        }
        public function manage_subjects(){
            $data = [
                'subjects'  => $this->subjectModel->findAll(),
                'pageTitle' => 'Manage Recorded Subjects',
            ];
            return view('admin/subjects/manage', $data);
        }
        public function add_subjectsToStream(){
            $data = [
                'subjects'   => $this->subjectModel->findAll(),
                'streams'   => $this->streamModel->getAllClasses(),
                'details'   => $this->sbStreamModel->get_details(),
                'pageTitle' => 'Assign Subjects on Streams',
            ];
            return view('admin/subjects/assign_subject', $data);
        }
        public function save_subjectsToStream(){
            $stream     = $this->request->getPost('stream');
            $subject    = $this->request->getPost('subject');
            $mandatory  = $this->request->getPost('mandatory');
            $subsidiary = $this->request->getPost('subsidiary');
            $practical  = $this->request->getPost('practical');
            if ($subsidiary != 1) {
                $subsidiary=null;
            }
            if ($practical != 1) {
                $practical=null;
            }
            $data = [
                'stream'     => $stream,
                'subject'    => $subject,
                'mandatory'  => $mandatory,
                'subsidiary' => $subsidiary,
                'practical'  => $practical,
                'created'    => date('Y-m-d H:i:s')
            ];
            $query = $this->sbStreamModel->add_subject($data);
            if ($query) {
                return redirect()->back()->with('success', 'Associated Successfully');
            }else{
                return redirect()->back()->with('error', 'Somthing went wrong Failed to Associate');
            }
        }
        public function getStreamReport(){
            return $this->subjectStreamsRepoet();
        }
        protected function subjectStreamsRepoet($category=null, $class=null, $stream=null){
            if ($category == null && $class == null && $stream ==null ) {
                $classes = $this->classModel->findAll();
                $streams = $this->streamModel->getAllClasses();
                return view('admin/liabilities/reports', ['pageTitle'=>'Query Report', 'classes'=>$classes, 'streams'=>$streams]);
            }else{

            }
        }
        protected function getSubjectId($name='')        {
            foreach($this->subjectModel->myId($name) as $row){
                $id = $row['subID'];
            }
            if ($id){ return $id; }else{ return '0'; }
        }

    /*================================================================================================
            EXAMINATION MODULE
    =================================================================================================*/
        protected function getClassMasterComment($total_score, $max_total){
            if ($max_total <= 0) {
                return "Mpangilio wa alama si Sahihi.";
            }
            // Hesabu asilimia
            $percent = ($total_score / $max_total) * 100;
            $percent = round($percent, 2);
            // Maoni ya Mkuu wa Shule kulingana na kiwango cha ufaulu
            if ($percent >= 96) {
                return "Amefaulu kiwango cha juu. Aendelee na juhudi, nidhamu na bidii zaidi ili kufikia na ndoto zake.";
            }elseif ($percent >= 80) {
                return "Amefanya vizuri sana. Aendelee na juhudi, nidhamu na bidii zaidi.";
            } 
            elseif ($percent >= 70) {
                return "Matokeo mazuri sana. Aendelee kuongeza juhudi ili kufikia kiwango cha juu zaidi.";
            } 
            elseif ($percent >= 60) {
                return "Amefanya vizuri. Ajitahidi zaidi ili kuongeza kiwango cha ufaulu.";
            } 
            elseif ($percent >= 50) {
                return "Amefanya kwa kiwango cha kati. Aongeze bidii na umakini katika masomo.";
            } 
            elseif ($percent >= 40) {
                return "Ufaulu wa kawaida. Inahitajika kuongezwa kwa juhudi na nidhamu ya masomo.";
            } 
            elseif ($percent >= 30) {
                return "Ufaulu wa chini. Mwanafunzi anapaswa kujitahidi zaidi na asaidiwe kwa karibu.";
            } 
            else {
                return "Ufaulu duni sana. Anahitaji msaada wa haraka na ufuatiliaji wa karibu ili kuboresha ufaulu.";
            }
        }
        protected function getHeadComment($total_score, $max_total){
            if ($max_total < 0) {
                return "Matokeo yachakatwe upya tafadhali";
            }
            // Hesabu asilimia
            $percent = ($total_score / $max_total) * 100;
            $percent = round($percent, 2);
            // Maoni ya Mkuu wa Shule kulingana na kiwango cha ufaulu
            if ($percent >= 96) {
                return "Ongera, vizuri Sana";
            }elseif ($percent >= 85) {
                return "Vizuri sana.";
            } 
            elseif ($percent >= 65) {
                return "Vizuri.";
            } 
            elseif ($percent >= 45) {
                return "Wastani.";
            }  
            elseif ($percent >= 30) {
                return "Ufaulu wa chini.";
            } 
            else {
                return "Ufaulu duni sana.";
            }
        }
        protected function grade($score=null) {
            //Check score range validit
            if ($score <0 || $score>100) {
                $grade = null;
            }else{
                //Grade Score
                if ($score >= 75) {
                    $grade = 'A';
                }elseif ($score >= 65) {
                    $grade = 'B';
                }elseif ($score >= 45) {
                    $grade = 'C';
                }elseif ($score >= 30) {
                    $grade = 'D';
                }elseif ($score >= 00) {
                    $grade = 'F';
                }
            }
            return $grade;
        }
        protected function adv_grade($score=null) {
            if ($score <0 || $score>100) {
                $grade = null;
            }else{
                //Grade Score
                if ($score >= 80) {
                    $grade = 'A';
                }elseif ($score >= 70) {
                    $grade = 'B';
                }elseif ($score >= 60) {
                    $grade = 'C';
                }elseif ($score >= 50) {
                    $grade = 'D';
                }elseif ($score >= 40) {
                    $grade = 'E';
                }elseif ($score >= 35) {
                    $grade = 'S';
                }elseif ($score >= 00) {
                    $grade = 'F';
                }else{
                    $grade='';
                }
            }
            return $grade;
        }
        protected function remark($grade=null, $level=1, $cat='score', $lang='sw'){
            if (strtolower($cat) === "div" && $lang ==='en') {
                if ($grade === 'I') {
                    $remark = 'Excelent';
                }elseif ($grade === 'II') {
                    $remark = 'Very Good';
                }elseif ($grade === 'III') {
                    $remark = 'Good';
                }elseif ($grade === 'IV') {
                    $remark = 'Pass';
                }elseif ($grade === '0') {
                    $remark = 'Fail';
                }else{
                    $remark = '-';
                }
            }elseif (strtolower($cat) === "div" && $lang ==='sw') {
                if ($grade === 'I') {
                    $remark = 'Vizuri Sana';
                }elseif ($grade === 'II') {
                    $remark = 'Vizuri';
                }elseif ($grade === 'III') {
                    $remark = 'Wastani';
                }elseif ($grade === 'IV') {
                    $remark = 'Dhaifu';
                }elseif ($grade === '0') {
                    $remark = 'Dhaifu Sana';
                }else{
                    $remark = '-';
                }
            }else{
                if ($lang === 'en') {
                    if ($grade === null) {
                        $remark = 'NIL';
                    }elseif ($grade === 'A') {
                        $remark = 'Excelent';
                    }elseif ($grade === 'B') {
                        $remark = 'Very Good';
                    }elseif ($grade === 'C') {
                        $remark = 'Good';
                    }elseif ($grade === 'D') {
                       if ($level == 1) {
                           $remark = 'Pass';
                       }elseif($level == 2){
                            $remark = 'Average';
                       }
                    }elseif ($grade === 'E') {
                        if ($level == 1) {
                           $remark = 'Pass';
                       }elseif($level == 2){
                            $remark = 'Pass';
                       }
                    }elseif ($grade === 'F') {
                        $remark = 'Fail';
                    }elseif ($grade === 'S') {
                        $remark = 'Subsidiary';
                    }else{
                        $remark = ' ';
                    }
                }else{
                    if ($grade === null) {
                        $remark = 'N/A';
                    }elseif ($grade === 'A') {
                        $remark = 'Umahiri wa juu';
                    }elseif ($grade === 'B') {
                        $remark = 'Umahiri';
                    }elseif ($grade === 'C') {
                        if ($level == 1) {
                           $remark = 'Vizuri';
                       }elseif($level == 2){
                            $remark = 'Vizuri Sana';
                       }
                    }elseif ($grade === 'D') {
                       if ($level == 1) {
                           $remark = 'Dhaifu';
                       }elseif($level == 2){
                            $remark = 'Vizuri';
                       }
                    }elseif ($grade === 'E') {
                        if ($level == 1) {
                           $remark = 'Faulu';
                       }elseif($level == 2){
                            $remark = 'Faulu';
                       }
                    }elseif ($grade === 'F') {
                        $remark = 'Dhaifu Sana';
                    }elseif ($grade === 'S') {
                        $remark = 'Saidizi';
                    }else{
                        $remark = 'N/A';
                    }
                }
            }
            return $remark;
        }
        protected function getDivision($points = null, $level =1){
            if($level == 1):
                if ($points >=7 && $points <= 17) {
                    return 'I';
                }elseif ($points >= 18 && $points <= 21) {
                    return 'II';
                }elseif ($points >= 22 && $points <= 25) {
                    return 'III';
                }elseif ($points >= 26 && $points <= 33) {
                    return 'IV';
                }elseif ($points >= 34 && $points <= 35) {
                    return '0';
                }else{
                    return 'N/A';
                }
            else:
                if ($points >=3 && $points <= 9) {
                    return 'I';
                }elseif ($points >= 10 && $points <= 12) {
                    return 'II';
                }elseif ($points >= 13 && $points <= 17) {
                    return 'III';
                }elseif ($points >= 18 && $points <= 19) {
                    return 'IV';
                }elseif ($points >= 20 && $points <= 21) {
                    return '0';
                }else{
                    return 'N/A';
                }
            endif;
        }
        protected function gradeToPoints($grade, $level=1){
            if ($level ==1) {
                $map = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'F' => 5];
                return $map[$grade] ?? 0;
            }elseif ($level == 2) {
                $map = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'S'=>6, 'F'=>7];
                return $map[$grade] ?? 0;
            }   
        }
        protected function exam_id($class){
            $code = 'EX';
            $level = $this->classModel->getLevel($class);
            if($level == 1 ){
                $level ='OL';
            }elseif($level == 2){
                $level = 'AL';
            }else{
                return false;
            }
            $id = $code.$level.$class;
            $num = $this->examModel->countAllResults();
            $num +=1;
            if ($num < 9) {
                $id = $id.'0000'.$num;
            }elseif ($num < 99) {
                $id = $id.'000'.$num;
            }elseif ($num < 999) {
                $id = $id.'00'.$num;
            }elseif ($num < 9999) {
                $id = $id.'0'.$num;
            }else{
                $id = $id.$num;
            }
            return $id;
        }
        protected function editExamStatus($exam_id, $status){
            $builder = $this->db->table('exams');
            $builder->where('exID', $exam_id);
            $builder->update(['exStatus' => $status]);

            return $this->db->affectedRows() > 0; // true if update worked
        }
        protected function classInSwahili($name){
            $name = strtolower($name);
            if($name === 'form one') return 'Kwanza';
            if($name === 'form two') return 'Pili';
            if($name === 'form three') return 'Tatu';
            if($name === 'form four') return 'Nne';
            if($name === 'form five') return 'Tano';
            if($name === 'form six') return 'Sita';
        }
        public function exams(){
            $data = [
                'years' => $this->ayModel->findAll(),
                'exams' => $this->examModel->getExam(),
                'pageTitle'=>'Examinations',
                'classes'  =>$this->classModel->getAll()
            ];
            return view('admin/exams/view', $data);
        }
        public function exam_setup(){
            $data = [
                'years' => $this->ayModel->findAll(),
                'pageTitle'=>'Examination\'s Setting',
                'classes'  =>$this->classModel->getAll()
            ];
            return view('admin/exams/set', $data);
        }
        public function exam_edit($exam){
            if($this->editExamStatus($exam, 'progress')):
                return redirect()->back()->with('success', 'Updated Successfully');
            else:
               return redirect()->back()->with('error', 'Something went wrong!');
            endif; 
        }
        public function getSubjectsByClass($classId=null) {
            $subjects = $this->sbStreamModel
                        ->getSubjectBelong($classId);
            return json_encode($subjects);
        }
        public function saveExam()     {
            $id = $this->exam_id($this->request->getPost('class'));
            if ($id === false) {
                return redirect()->back()->with('error', "No such class in system please concert the System Administrator");
            }
            $subjects = $this->getSubjectsByClass($this->request->getPost('class'));
            $name = $this->request->getPost('name');
            $data = [
                'id'   => $id,
                'name' => $name,
                'term_id' => $this->request->getPost('terms'),
                'class' => $this->request->getPost('class'),
                'subjects' => $this->getSubjectsByClass($this->request->getPost('class')),
                'created' => date('Y-m-d H:i:s'),
                'status' =>  'Next'
            ];
            $examId = $this->examModel->add($data);
            if ($examId) {
                $data = 'ID:'.$id.' name:'.$name;
                return redirect()->back()->with('success', $data.' Added Successfully');
            }else{
               $data = 'Something went wrong! Exam:'.$name.' Not recorded.';
                return redirect()->back()->with('error', $data); 
            }            
        }
        public function record_score(){
            $data = [
                'years' => $this->ayModel->findAll(),
                'exams' => $this->examModel->getExam('progress'),
                'pageTitle'=>'Record Examination Results',
                'classes'  =>$this->classModel->getAll()
            ];
            return view('admin/exams/record_score', $data);
        }
        public function score_sheet($class_id, $exam_id){
            $db = \Config\Database::connect();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $students = $this->studentModel->getByClass($class_id);
            $exam = $db->table('exams')->where('exID', $exam_id)->get()->getRowArray();
            $subjects = json_decode($exam['exSubjects']);
            $sheet->setCellValue('A1', 'admno');
            $sheet->setCellValue('B1', 'Full Name');
            $subjectSet = [];
            foreach ($exam as &$res) {
                $decoded = json_decode($res['exSubjects'], true);
                $res['decoded'] = $decoded;
                foreach ($decoded['subjects'] as $subject) {
                    $subjectSet[$subject['subject']] = true;
                }
            }
            $subjects = array_keys($subjectSet);

            $col = 'B';
            $cont = 0;
            foreach($subjects as $sub){
                $sheet->setCellValue($col++.'1', $sub['subject']);
                $cont++;
            } 
            $row =2; 
            foreach($students as $st){
                $sheet->setCellValue('A'.$row, $st['id']);
                $sheet->setCellValue('B'.$row, strtoupper($st['name']));
                $row++;
            }

            $sheet->getColumnDimension('B')->setWidth(23);

            $writer = new Xlsx($spreadsheet);
            $filename = 'student_template.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        }
        public function up_score_sheet(){
            ini_set('max_execution_time', 3000);
            $exam_id = $this->request->getPost('exam');
            $class_id = $this->request->getPost('class');
            $file = $this->request->getFile('sheet');
            $level = $this->examModel->getLevel($exam_id);

            if (! $file->isValid()) {
                return redirect()->back()->with('error', 'Invalid file uploaded.');
            }
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Map subject columns: skip first two columns (A=student_id, B=student_name)
            $header = array_shift($sheet); 
            $subjectCols = [];
            foreach ($header as $col => $value) {
                $value = trim($value);
                if ($col !== 'A' && $col !== 'B' && $value !== '') {
                    $subjectCols[$col] = $value;
                }
            }

            $success = 0;
            $failed  = 0;

            foreach ($sheet as $row) {
                $stuID = trim($row['A']);
                if (empty($stuID)) continue;

                foreach ($subjectCols as $col => $subName) {
                    $cellValue = trim((string) ($row[$col] ?? ''));
                    if ($cellValue === '') continue;  // skip empty scores

                    if (!is_numeric($cellValue)) {
                        $failed++;
                        continue;
                    }

                    $score = (float)$cellValue;
                    $grade = (strtolower($level) === 'a-level') ? $this->adv_grade($score) : $this->grade($score);
                    $subject_id = $this->getSubjectId($subName);
                    if (!$subject_id) {
                        $failed++;
                        continue;
                    }

                    $data = [
                        'exam'     => $exam_id,
                        'student'  => $stuID,
                        'subject'  => $subject_id,
                        'score'    => $score,
                        'grade'    => $grade,
                        'date'     => date('Y-m-d H:i:s'),
                        'creator'  => session()->get('user_id'),
                        'status'   => 'raw',
                    ];

                    if ($this->rawResultModel->add_score($data)) {
                        $success++;
                    } else {
                        $failed++;
                    }
                }
            }

            $message = "Upload complete: {$success} valid scores saved, {$failed} failed.";
            return redirect()->back()->with('success', $message);
        }      
        public function download_score_template($exam_id, $class_id){
            $db = \Config\Database::connect();

            // Fetch students in class
            $students = $this->studentModel->getByClass($class_id);
            if(empty($students)){
                return redirect()->back()->with('error','No students found for this class.');
            }
            // Fetch exam info
            $exam = $db->table('exams')->where('exID', $exam_id)->get()->getRowArray();
            if(!$exam){
                return redirect()->back()->with('error','Exam not found.');
            }
            // Fetch subjects for the class
            $subjects = $this->sbStreamModel->getSubjectBelong($class_id);
            if(empty($subjects)){
                return redirect()->back()->with('error','No subjects found for this class.');
            }
            // Create Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Score Template');

            // Header row
            $col = 'A';
            $sheet->setCellValue($col++.'1','Reg_no');
            $sheet->setCellValue($col++.'1','Full_Name');

            foreach($subjects as $sub){
                $sheet->setCellValue($col++.'1', $sub['abrev']);
            }

            // Fill students
            $row = 2;
            foreach($students as $stu){
                $col = 'A';
                $sheet->setCellValue($col++.$row, $stu['id']);
                $sheet->setCellValue($col++.$row, strtoupper($stu['name']));
                $row++;
            }

            // Column widths
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(10);

            // Download
            $writer = new Xlsx($spreadsheet);
            $filename = 'Exam_Score_Template_'.$class_id.'_'.$exam_id.'.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        }
        public function compile_exam_results($exam_id, $class_id){
            helper('text');
            $user_id = session()->get('user_id');
            if(empty($user_id) || !isset($user_id)) return $this->logout();
            $level   = $this->examModel->getLevel($exam_id); // o-level or a-level
            if (strtolower($level) === "a-level") {
                $rs = $this->compile_Adv_exam_results($exam_id, $class_id, $level);
            }else{
                $rs = $this->compile_Ord_exam_results($exam_id, $class_id, $level);
            }

            if ($rs === true) {
                $this->generate($exam_id);
                return redirect()->back()->with('success', 'Results compiled successfully!');
            }else{
                return redirect()->back()->with('Error', 'Compiration failed');
            }
        }//END OF compile_exam_results
        public function compile_Ord_exam_results($exam_id, $class_id, $level){
            helper('text');
            $user_id = session()->get('user_id');
            $level = strtolower($level);

            // Fetch students
            $students = $this->studentModel->getByClass($class_id);
            if (empty($students)) {
                return redirect()->back()->with('error', 'No students found for this class.');
            }

            $compiledData = [];
            foreach ($students as $stu) {
                $stuID     = $stu['id'];
                $stream_id = $stu['stream'];
                // =====================================================
                // FETCH SUBJECT MAPPING
                // =====================================================
                $streamSubjects = $this->sbStreamModel->getSubjects($stream_id);
                $mandatoryIDs  = [];
                $optionalIDs   = [];
                foreach ($streamSubjects as $s) {
                    if ($s['isMandatory'])      $mandatoryIDs[]  = $s['subject_id'];
                    else                        $optionalIDs[]   = $s['subject_id'];
                }

                // =====================================================
                // RAW RESULTS INDEXED BY SUBJECT_ID
                // =====================================================
                $rawResults = $this->rawResultModel
                    ->where('exam_id', $exam_id)
                    ->where('student_id', $stuID)
                    ->findAll();

                $rawBySub = [];
                foreach ($rawResults as $r) {
                    $rawBySub[$r['subject_id']] = $r;
                }

                $subjects = [];
                $totalScore = 0;
                $attemptedCount = 0; 

                // =====================================================
                // 1. MANDATORY SUBJECTS  (Always PRINCIPLE for A-level)
                // =====================================================
                foreach ($mandatoryIDs as $subID) {

                    $info = $this->subjectModel->find($subID);
                    $name = $info['subShort'] ?? 'Unknown';

                    if (!isset($rawBySub[$subID])) {
                        // ABSENT
                        $subjects[] = [
                            'subject' => $name,
                            'score'   => null,
                            'grade'   => 'AB',
                            'points'  => 999
                        ];
                        continue;
                    }

                    $r = $rawBySub[$subID];
                    $score = $r['raScore'];
                    $grade = $r['raGrade'];

                    $points = $this->gradeToPoints($grade, 1); // O-level
                    $subjects[] = [
                        'subject' => $name,
                        'score'   => $score,
                        'grade'   => $grade,
                        'points'  => $points
                    ];

                    $totalScore += $score;

                    $attemptedCount++;
                }

                // =====================================================
                // 2. OPTIONAL SUBJECTS (Principal at A-level)
                // =====================================================
                foreach ($optionalIDs as $subID) {
                    $info = $this->subjectModel->find($subID);
                    $name = $info['subShort'] ?? 'Unknown';

                    // O-level: ignore optional not attempted
                    if ($level === 'o-level' && !isset($rawBySub[$subID])) continue;

                    if (!isset($rawBySub[$subID])) {
                        $subjects[] = [
                            'subject' => $name,
                            'score'   => null,
                            'grade'   => 'N/A',
                            'points'  => 999
                        ];
                        continue;
                    }

                    $r = $rawBySub[$subID];
                    $score = $r['raScore'];
                    $grade = $r['raGrade'];

                    $points = $this->gradeToPoints($grade, 1);

                    $subjects[] = [
                        'subject' => $name,
                        'score'   => $score,
                        'grade'   => $grade,
                        'points'  => $points
                    ];

                    $totalScore += $score;

                    $attemptedCount++;
                }

                // =====================================================
                // 3. COMPLETENESS CHECK (FIXED)
                // =====================================================
                if ($level === 'o-level') {
                    $isComplete = ($attemptedCount >= 7);
                }

                // =====================================================
                // 4. DIVISION COMPUTATION (FIXED)
                // =====================================================
                $points = 0;
                $division = "INCOMPLETE";
                //!Remove ABSENT BEFORE ANY CALCULATION
                $validSubjects = array_filter($subjects, function($s){
                    return $s['points'] <999; //Skip ABST (999)
                });

                if ($level === 'o-level') {
                    // SORT all subjects by BEST (lowest points first)
                    usort($validSubjects, fn($a, $b) => $a['points'] <=> $b['points']);
                    if($isComplete){
                        $best = array_slice($validSubjects, 0, 7);
                        $points = array_sum(array_column($best, 'points'));
                        $division = $this->getDivision($points, 1);
                    }else{
                        //$points = array_sum(array_column($best, 'points'));
                        $points = 0;
                        $division = "Inc";
                    }
                }

                // =====================================================
                // 5. REMARK + AVERAGE SCORE
                // =====================================================
                $remark = ($division === 'INCOMPLETE') ? 'Incomplete' : $this->remark($division, 1, 'div', 'en');
                $remark_sw = ($division === "INCOMPLETE") ? 'Pungufu' : $this->remark($division, 1, 'div', 'sw');
                $attemptedSubjects = array_filter($subjects, fn($s) => $s['score'] !== null);
                $avgScore = count($attemptedSubjects)
                    ? round($totalScore / count($attemptedSubjects), 2)
                    : 0;

                // =====================================================
                // 6. PACK JSON
                // =====================================================
                $crResult = [
                    'subjects' => $subjects,
                    'points'   => $points,
                    'division' => $division,
                    'remark'   => $remark,
                    'total'    => $totalScore,
                    'average'  => $avgScore,
                    'complete' => $isComplete ? 1 : 0
                ];

                $compiledData[] = [
                    'exam_id'      => $exam_id,
                    'student_id'   => $stuID,
                    'crResults'    => json_encode($crResult),
                    'crCreated'    => date('Y-m-d H:i:s'),
                    'crCompiledBy' => $user_id,
                    'crPoints'     => $points,
                    'crDivision'   => $division,
                    'crRemarks'    => $remark,
                    'crTotal'      => $totalScore,
                    'crAverage'    => $avgScore,
                    'crMaoni'      => $remark_sw,
                    'raStatus'     => 'compiled'
                ];
            }

            // =====================================================
            // DELETE OLD RESULTS + INSERT NEW
            // =====================================================
            $studentIDs = $this->db->table('students s')
                ->select('s.stuID')
                ->join('streams st', 'st.sid = s.stream_id')
                ->where('st.class_id', $class_id)
                ->get()->getResultArray();

            $ids = array_column($studentIDs, 'stuID');

            if (!empty($ids)) {
                $this->compiledResultsModel
                    ->where('exam_id', $exam_id)
                    ->whereIn('student_id', $ids)
                    ->delete();
            }

            if (!empty($compiledData)) {
                $this->compiledResultsModel->insertBatch($compiledData);

                $this->rawResultModel
                    ->where('exam_id', $exam_id)
                    ->whereIn('student_id', $ids)
                    ->set(['raStatus' => 'compiled'])
                    ->update();
            }

            //Rank students
            $results = $this->compiledResultsModel->where('exam_id', $exam_id)
                            ->orderBy('crAverage', 'DESC')
                            ->orderBy("
                                    CASE
                                        WHEN crDivision = 'Inc' THEN 999
                                        WHEN crDivision = '0' THEN 998
                                        WHEN crDivision = 'IV' THEN 4
                                        WHEN crDivision = 'III' THEN 3
                                        WHEN crDivision = 'II' THEN 2
                                        WHEN crDivision = 'I' THEN 1
                                        ELSE 1000
                                    END ASC", '', false
                                )
                            ->orderBy('crPoints', 'ASC')
                            ->findAll();
            $position = 1;
            foreach ($results as $row) { 
                $this->compiledResultsModel
                     ->update($row['crID'], ['crPosition' => $position, 'raUpdated'=>date('Y-m-d H:i:s')], ); 
                $position++; 
            }
                            

            $this->examModel
                ->where('exID', $exam_id)
                ->set(['exStatus' => 'current'])
                ->update();
            return true;
        }//END OF compile_Ord_exam_results
        public function compile_Adv_exam_results($exam_id, $class_id, $level){
            helper('text');
            $user_id = session()->get('user_id');
            $level   = $this->examModel->getLevel($exam_id); // 'O-level' or 'A-level'

            // Get students in this class
            $students = $this->studentModel->getByClass($class_id);
            if (empty($students)) {
                return redirect()->back()->with('error', 'No students found for this class.');
            }
            $compiledData = [];
            foreach ($students as $stu) {

                $stuID     = $stu['id'];
                $stream_id = $stu['stream'];

                // ----------------------------------------
                // FETCH SUBJECT STRUCTURE FOR THE STREAM
                // ----------------------------------------
                $streamSubjects = $this->sbStreamModel->getSubjects($stream_id);

                $mandatoryIDs  = [];
                $subsidiaryIDs = [];
                $optionalIDs   = [];

                foreach ($streamSubjects as $sub) {
                    if ($sub['isMandatory']) {
                        $mandatoryIDs[] = $sub['subject_id'];
                    } 
                    elseif ($sub['isSubsidiary']) {
                        $subsidiaryIDs[] = $sub['subject_id'];
                    } 
                    else {
                        $optionalIDs[] = $sub['subject_id'];
                    }
                }

                // ----------------------------------------
                // FETCH RAW RESULTS FOR THIS STUDENT
                // ----------------------------------------
                $rawResults = $this->rawResultModel
                    ->where('exam_id', $exam_id)
                    ->where('student_id', $stuID)
                    ->findAll();

                // Index by subject for quick lookup
                $rawBySub = [];
                foreach ($rawResults as $r) {
                    $rawBySub[$r['subject_id']] = $r;
                }

                $subjects = [];    // holds all subjects to be saved
                $isComplete = true;

                $totalScore = 0;

                // =====================================================
                // 1. HANDLE MANDATORY SUBJECTS
                // =====================================================
                foreach ($mandatoryIDs as $subID) {
                    $info   = $this->subjectModel->find($subID);
                    $subName = $info ? $info['subShort'] : 'Unknown Subject';

                    if (!isset($rawBySub[$subID])) {
                        // missing mandatory → incomplete
                        $isComplete = false;

                        $subjects[] = [
                            'subject' => $subName,
                            'score'   => null,
                            'grade'   => 'AB',
                            'points'  => 0
                        ];
                        continue;
                    }

                    $r      = $rawBySub[$subID];
                    $grade  = $r['raGrade'];
                    $score  = $r['raScore'];

                    $points = (strtolower($level) === 'a-level')
                        ? $this->gradeToPoints($grade, 2)
                        : $this->gradeToPoints($grade, 1);

                    $subjects[] = [
                        'subject' => $subName,
                        'score'   => $score,
                        'grade'   => $grade,
                        'points'  => $points
                    ];

                    $totalScore += $score;
                }

                // =====================================================
                // 2. HANDLE SUBSIDIARY SUBJECTS (A-level ONLY)
                // =====================================================
                if (strtolower($level) === 'a-level') {
                    foreach ($subsidiaryIDs as $subID) {
                        $info    = $this->subjectModel->find($subID);
                        $subName = $info ? $info['subShort'] : 'Unknown Subject';

                        if (!isset($rawBySub[$subID])) {
                            // Include unattempted subsidiary with zeros
                            $subjects[] = [
                                'subject' => $subName,
                                'score'   => null,
                                'grade'   => 'N/A',
                                'points'  => 0
                            ];
                            continue;
                        }

                        $r     = $rawBySub[$subID];
                        $score = $r['raScore'];
                        $grade = $r['raGrade'];

                        // Subsidiary always 0 points
                        $subjects[] = [
                            'subject' => $subName,
                            'score'   => $score,
                            'grade'   => $grade,
                            'points'  => 0
                        ];

                        $totalScore += $score;
                    }
                }

                // =====================================================
                // 4. BEST SUBJECT CALCULATIONS
                // =====================================================

                /** FINAL DIVISION VARIABLES */
                $points = 0;

                // Identify principal subjects (exclude subsidiary)
                $principal = [];
                foreach ($subjects as $s) {
                    foreach ($streamSubjects as $st) {
                        if ($st['short'] === $s['subject'] && $st['isSubsidiary'] == 0) {
                            $principal[] = $s;
                            break;
                        }
                    }
                }

                // Sort highest → lowest points
                usort($principal, fn($a,$b) => $b['points'] <=> $a['points']);

                // Best 3
                $best3 = array_slice($principal, 0, 3);

                $points = array_sum(array_column($best3, 'points'));

                $division = $isComplete
                    ? $this->getDivision($points, 2)
                    : 'INCOMPLETE';
                    
                // =====================================================
                // 5. REMARK + AVERAGE
                // =====================================================
                $remark = ($division === 'INCOMPLETE')
                    ? 'Incomplete'
                    : $this->remark($division, 1, 'div', 'en');
                $remark_sw = ($division === 'INCOMPLETE')? 'Pungufu' : $this->remark($division, 1, 'div', 'sw');

                $attempted = array_filter($subjects, fn($s) => $s['score'] !== null);
                $avgScore = count($attempted) ? round($totalScore / count($attempted), 2) : 0;

                // =====================================================
                // 6. PACKAGE JSON DATA
                // =====================================================
                $crResult = [
                    'subjects' => $subjects,
                    'points'   => $points,
                    'division' => $division,
                    'remark'   => $remark,
                    'total'    => $totalScore,
                    'average'  => $avgScore,
                    'complete' => $isComplete ? 1 : 0
                ];

                $compiledData[] = [
                    'exam_id'      => $exam_id,
                    'student_id'   => $stuID,
                    'crResults'    => json_encode($crResult),
                    'crCreated'    => date('Y-m-d H:i:s'),
                    'crCompiledBy' => $user_id,
                    'crTotal'      => $totalScore,
                    'crAverage'    => $avgScore,
                    'crPoints'     => $points,
                    'crDivision'   => $division,
                    'crRemarks'    => $remark,
                    'crMaoni'     => $remark_sw,
                    'raStatus'     => 'compiled'
                ];
            }
            // -----------------------------------------------------
            // DELETE OLD COMPILED RESULTS FOR THIS CLASS + EXAM
            // -----------------------------------------------------
            $studentIDs = $this->db->table('students s')
                ->select('s.stuID')
                ->join('streams st', 'st.sid = s.stream_id')
                ->where('st.class_id', $class_id)
                ->get()->getResultArray();

            $ids = array_column($studentIDs, 'stuID');

            if (!empty($ids)) {
                $this->compiledResultsModel
                    ->where('exam_id', $exam_id)
                    ->whereIn('student_id', $ids)
                    ->delete();
            }

            // SAVE NEW RESULTS
            if (!empty($compiledData)) {

                $this->compiledResultsModel->insertBatch($compiledData);

                $this->rawResultModel
                    ->where('exam_id', $exam_id)
                    ->whereIn('student_id', $ids)
                    ->set(['raStatus' => 'compiled'])
                    ->update();
            }


            /*---------------------------------------------------------------------------------
              RANK RESULTS ACCORDING TO DIVISION AND AVERAGE
            -----------------------------------------------------------------------------------*/
            $results = $this->compiledResultsModel
                            ->where('exam_id', $exam_id)
                            ->orderBy('crAverage', 'DESC') 
                            ->orderBy(" 
                                CASE crDivision 
                                    WHEN 'I' THEN 1 
                                    WHEN 'II' THEN 2 
                                    WHEN 'III' THEN 3 
                                    WHEN 'IV' THEN 4 
                                    WHEN '0' THEN 5 
                                    WHEN 'Inc' THEN 6 
                                    ELSE 7 
                                END ", '', false)
                            ->orderBy('crPoints', 'ASC')
                            ->orderBy('student_id', 'ASC')
                            ->findAll();
            $position = 1;
            foreach ($results as $row) { 
                $this->compiledResultsModel
                     ->update($row['crID'], ['crPosition' => $position, 'raUpdated'=>date('Y-m-d H:i:s')], ); 
                $position++; 
            }

            // Mark EXAM as current
            $this->examModel
                ->where('exID', $exam_id)
                ->set(['exStatus' => 'current'])
                ->update();

                return true;
        }//END OF compile_Adv_exam_results
        public function generate($exam_id){
            // Detect level (example logic)
            $level = $this->examModel->getLevel($exam_id);
            $model = $this->examSubjectSummaryModel->compileSubjectSummary($exam_id, $level);
        }
        //View results
        public function view_analytice($exam_id)   {
            $data['subjects'] = $this->examSubjectSummaryModel->getSummary($exam_id);
            $data['exam_id']  = $exam_id;
            $data['info'] = $this->examModel->info($exam_id);
            $data['pageTitle'] ='Results Analytics';
            $results= $this->compiledResultsModel->resultSummary($exam_id);
            $summary = [];
            $grand_total = [
                'I' => 0,
                'II' => 0,
                'III' => 0,
                'IV' => 0,
                '0' => 0,
                'Inc' => 0,
                'TOTAL' => 0
            ];
            // Initialize structure
            foreach ($results as $row) {
                $sex = $row['stuSex'];
                $division = $row['crDivision'];
                $count = $row['COUNT(crID)'];

                if (!isset($summary[$sex])) {
                    $summary[$sex] = [
                        'I' => 0,
                        'II' => 0,
                        'III' => 0,
                        'IV' => 0,
                        '0' => 0,
                        'Inc' => 0,
                        'TOTAL' => 0
                    ];
                }

                if (isset($summary[$sex][$division])) {
                    $summary[$sex][$division] = $count;
                }else{
                    $summary[$sex][ucfirst(strtolower($division))] = $count;
                }

                // row total
                $summary[$sex]['TOTAL'] += $count;

                // grand totals
                if (isset($grand_total[$division])){
                    $grand_total[$division] += $count;
                }else{
                    $grand_total[ucfirst(strtolower($division))] += $count;
                }
                $grand_total['TOTAL'] += $count;
            }

            $data['general_results'] = $summary;
            $data['grand_total'] = $grand_total;
            return view('admin/exams/result_summary', $data);
        }
        public function print_analytice($exam_id){
            $data['info'] = $this->examModel->info($exam_id);
            $results = $this->compiledResultsModel->resultSummary($exam_id);
            $subjects = $this->examSubjectSummaryModel->getSummary($exam_id);

            $summary = [];
            $grand_total = [
                'I'=>0,'II'=>0,'III'=>0,
                'IV'=>0,'0'=>0,'Inc'=>0,'TOTAL'=>0
            ];

            foreach ($results as $row) {
                $sex = $row['stuSex'];
                $division = $row['crDivision'];
                $count = $row['COUNT(crID)'];

                if (!isset($summary[$sex])) {
                    $summary[$sex] = [
                        'I'=>0,'II'=>0,'III'=>0,
                        'IV'=>0,'0'=>0,'Inc'=>0,'TOTAL'=>0
                    ];
                }

                if (isset($summary[$sex][$division])) {
                    $summary[$sex][$division] = $count;
                }else{
                    $summary[$sex][ucfirst(strtolower($division))] = $count;
                }
                $summary[$sex]['TOTAL'] += $count;

                if (isset($grand_total[$division])){
                    $grand_total[$division] += $count;
                }else{
                    $grand_total[ucfirst(strtolower($division))] += $count;
                }
                $grand_total['TOTAL'] += $count;
            }

            $filename = $data['info']['exam'].'_'.$data['info']['class'].'_'.$data['info']['year'];

            $logoLeft = FCPATH . 'images/tz_arm.jpeg';   // coat of arms
            $logoRight = FCPATH . 'images/logo.jpg';    // school logo

            $header = "
                <div style='border-bottom:1px solid #000; margin-top:1px;'>
                <table width='100%' style='border-collapse:collapse; border:none;'>
                    <tr>
                        <td width='20%' style='text-align:left; border:none;'>
                            <img src='{$logoLeft}' height='100' width='100'>
                        </td>

                        <td width='60%' style='text-align:center; font-weight:bold; border:none;'>
                            <div style='font-size:14px;'>
                                OFISI YA WAZIRI MKUU,<br>
                                TAWALA ZA MIKOA NA SERIKALI ZA MITAA
                            </div>
                            <div style='font-size:13px; font-style: italic;'>
                                HALMASHAURI YA WILAYA YA BUKOBA<br>
                                SHULE YA SEKONDARI LYAMAHORO
                            </div>
                            <div style='font-size:12px; margin-top:5px;'>
                                ".strtoupper($filename)."
                            </div>
                        </td>

                        <td width='20%' style='text-align:right; border:none;'>
                            <img src='{$logoRight}' height='100' width='100'>
                        </td>
                    </tr>
                </table>
                </div><br>
                ";
            $footer = "
                <div style='text-align:center; font-size:10px;'>
                    Printed on ".date('d M Y H:i:s')." | Page {PAGENO}/{nb}
                </div>
            ";

            // ================= HTML =================
            $html = '
            <style>
                table { width:100%; border-collapse: collapse; margin-bottom:20px; }
                th, td { border:1px solid #000; padding:6px; text-align:center; }
                th { background:#eee; }
                h3 { text-align:center; margin-bottom:5px; }
            </style>
            ';

            // ===== DIVISION TABLE =====
            $html .= '<br><h3>DIVISION PERFORMANCE SUMMARY</h3>';
            $html .= '<table>
                <thead>
                    <tr>
                        <th>SEX</th><th>I</th><th>II</th><th>III</th>
                        <th>IV</th><th>0</th><th>Inc</th><th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($summary as $sex => $divisions) {
                $html .= "
                <tr>
                    <td>{$sex}</td>
                    <td>{$divisions['I']}</td>
                    <td>{$divisions['II']}</td>
                    <td>{$divisions['III']}</td>
                    <td>{$divisions['IV']}</td>
                    <td>{$divisions['0']}</td>
                    <td>{$divisions['Inc']}</td>
                    <td><strong>{$divisions['TOTAL']}</strong></td>
                </tr>";
            }

            $html .= '</tbody>
                <tfoot>
                    <tr>
                        <td><strong>GRAND TOTAL</strong></td>
                        <td>'.$grand_total['I'].'</td>
                        <td>'.$grand_total['II'].'</td>
                        <td>'.$grand_total['III'].'</td>
                        <td>'.$grand_total['IV'].'</td>
                        <td>'.$grand_total['0'].'</td>
                        <td>'.$grand_total['Inc'].'</td>
                        <td><strong>'.$grand_total['TOTAL'].'</strong></td>
                    </tr>
                </tfoot>
            </table>';

            // ===== SUBJECT TABLE =====
            $html .= '<h3>SUBJECTS PERFORMANCE</h3>';

            $allGrades = [];
            foreach ($subjects as $row) {
                foreach(($row['grades'] ?? []) as $grade => $count){
                    if (!in_array($grade, $allGrades)) {
                        $allGrades[] = $grade;
                    }
                }
            }

            $gradeOrder = ['A','B','C','D','E','S','F'];
            usort($allGrades, fn($a,$b)=>array_search($a,$gradeOrder)-array_search($b,$gradeOrder));

            $html .= "<table><thead><tr>
                <th>SUBJECT</th><th>SEAT</th>";

            foreach($allGrades as $g){
                $html .= "<th>{$g}</th>";
            }

            $html .= "<th>PASS</th><th>PASS%</th><th>GPA</th><th>GRADE</th>
                </tr></thead><tbody>";

            foreach($subjects as $row){
                $html .= "<tr>
                    <td>{$row['subName']}</td>
                    <td>{$row['seat']}</td>";

                foreach($allGrades as $g){
                    $num = (int)($row['grades'][$g] ?? 0);
                    $html .= "<td>{$num}</td>";
                }

                $html .= "<td>{$row['pass']}</td>
                    <td>".number_format($row['pass_percent'],1)."%</td>
                    <td>".number_format($row['gpa'],4)."</td>
                    <td>{$row['grade']}</td>
                </tr>";
            }

            $html .= "</tbody></table>";

            // ================= PDF =================
            $pdf = new \App\Libraries\PdfService();
            $pdf->generate($html, 'Analysis_'.$filename.'.pdf', $header, $footer);
        }

        public function viewClassResult($exam_id, $class_id){
            $db = \Config\Database::connect();
            $results = $db->table('compiledresults cr')
                          ->select('cr.*, concat(stuFname, " ", stuMname, " ", stuSurname) as student, st.sName as stream, st.class_id')
                          ->join('students s', 's.stuID = cr.student_id')
                          ->join('streams st', 'st.sid = s.stream_id')
                          ->where('cr.exam_id', $exam_id)
                          ->where('st.class_id', $class_id)
                          ->get()->getResultArray();
            $exam = $db->table('exams')->where('exID', $exam_id)->get()->getRowArray();
            $class = $db->table('classes')->where('cid', $class_id)->get()->getRowArray();
            return view('admin/exams/class_results', [
                            'exam' =>$exam,
                            'class' =>$class,
                            'results' =>$results,
                            'pageTitle' =>'Compiled Results -'.$class['named']
                        ]);
        }
        public function view_class_results($exam_id, $class_id){
            $db = \Config\Database::connect();
            // Get all compiled results for the class & exam
            $results = $this->compiledResultsModel->getResultByClass($class_id, $exam_id);

            if (!$results) {
                return redirect()->back()->with('error', 'No results found for this class.');
            }

            // Collect all unique subject names from JSON results
            $subjectSet = [];
            foreach ($results as &$res) {
                $decoded = json_decode($res['crResults'], true);
                $res['decoded'] = $decoded;
                foreach ($decoded['subjects'] as $subject) {
                    $subjectSet[$subject['subject']] = true;
                }
            }
            $subjects = array_keys($subjectSet);

            $exam = $db->table('exams')->where('exID', $exam_id)->get()->getRowArray();
            $class = $db->table('classes')->where('cid', $class_id)->get()->getRowArray();

            return view('admin/exams/class_results', [
                'exam' => $exam,
                'class' => $class,
                'results' => $results,
                'subjects' => $subjects,
                'pageTitle' => 'Results Sheet - '.$class['named']
            ]);
        }
        public function view_results(){
            $exam_id = $this->request->getPost('exam');
            $db = \Config\Database::connect();
            // Get all compiled results for the class & exam
            $results = $this->compiledResultsModel->examResults($exam_id);
            if (!$results) {
                return redirect()->back()->with('error', 'No results found for this class.');
            }            
            //Collect all unique subject names from JSON results
            $subjectSet = [];
            foreach ($results as &$res) {
                $decoded = json_decode($res['crResults'], true);
                $res['decoded'] = $decoded;
                foreach ($decoded['subjects'] as $subject) {
                    $subjectSet[$subject['subject']] = true;
                }
            }
            $subjects = array_keys($subjectSet);
            $exam = $db->table('exams')->where('exID', $exam_id)->get()->getRowArray();
            $class = $this->examModel->getClassInfo($exam_id);

            return view('admin/exams/view_results', [
                'exam' => $exam,
                'class' => $class,
                'results' => $results,
                'subjects' => $subjects,
                'pageTitle' => $class['exName'].' Results'
            ]);
        }
        public function view_result(){
            $data = [
                'pageTitle' => "Get Examination Results",
                'exams' => $this->examModel->listExam()
            ];
            return view('admin/exams/get_results', $data);
        }
        public function export_class_results_excel($exam_id, $class_id){
            $results = $this->compiledResultsModel->getResultByClass($class_id, $exam_id);
            if (!$results) return redirect()->back()->with('error', 'No results found.');
            // Collect subjects dynamically
            $subjectSet = [];
            foreach ($results as &$res) {
                $decoded = json_decode($res['crResults'], true);
                $res['decoded'] = $decoded;
                foreach ($decoded['subjects'] as $subject) {
                    $subjectSet[$subject['subject']] = true;
                }
            }
            $subjects = array_keys($subjectSet);

            // Prepare spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Class Results');

            // Header row
            $col = 'A';
            $sheet->setCellValue($col++.'1', 'Student');
            foreach ($subjects as $sub) $sheet->setCellValue($col++.'1', $sub);
            $sheet->setCellValue($col++.'1', 'Total');
            $sheet->setCellValue($col++.'1', 'Average');
            $sheet->setCellValue($col++.'1', 'Points');
            $sheet->setCellValue($col++.'1', 'Division');
            $sheet->setCellValue($col++.'1', 'Remark');

            // Fill data
            $row = 2;
            foreach ($results as $r) {
                $decoded = $r['decoded'];
                $subjectData = array_column($decoded['subjects'], null, 'subject');
                $col = 'A';
                $sheet->setCellValue($col++.$row, $r['student']);
                $total = array_sum(array_column($decoded['subjects'],'score') ?? [0]);
                $average = count($decoded['subjects'])>0 ? $total/count($decoded['subjects']) : 0;
                foreach ($subjects as $sub) {
                    $sheet->setCellValue($col++.$row, $subjectData[$sub]['score'] ?? '');
                }
                $sheet->setCellValue($col++.$row, $total);
                $sheet->setCellValue($col++.$row, $average);
                $sheet->setCellValue($col++.$row, $decoded['points']);
                $sheet->setCellValue($col++.$row, $decoded['division']);
                $sheet->setCellValue($col++.$row, $decoded['remark']);
                $row++;
            }

            // Output to browser
            $writer = new Xlsx($spreadsheet);
            $filename = 'class_results_'.$exam_id.'.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            $writer->save('php://output');
            exit;
        }
        public function export_class_results_pdf($class_id, $exam_id){
            $mode = $this->request->getGet('mode') ?? 'grade';
            $db = \Config\Database::connect();
            $user = session()->get('user_name');

            // Fetch compiled results
            $results = $this->compiledResultsModel->getResultByClass($class_id, $exam_id);

            if (empty($results)) {
                return redirect()->back()->with('error', 'No compiled results found.');
            }

            // Build subject set and decode
            $subjectSet = [];
            foreach ($results as &$res) {
                $decoded = json_decode($res['crResults'], true) ?: [];
                // normalize structure: ensure 'subjects' exists (backwards compatibility)
                if (!isset($decoded['subjects']) && isset($decoded['core'])) {
                    // If using A-level structure where 'core' exists, map to 'subjects' for export
                    $decoded['subjects'] = $decoded['core'];
                }
                $res['decoded'] = $decoded;
                if (!empty($decoded['subjects']) && is_array($decoded['subjects'])) {
                    foreach ($decoded['subjects'] as $subject) {
                        if (!empty($subject['subject'])) {
                            $subjectSet[$subject['subject']] = true;
                        }
                    }
                }
            }
            unset($res); // important to avoid lingering reference

            $subjects = array_keys($subjectSet);

            // Load metadata (safely)
            $school = $db->table('settings')->get()->getRowArray() ?? [
                'school_name' => 'School',
                'school_logo' => 'uploads/school_logo.png'
            ];
            $class  = $db->table('classes')->where('cid', $class_id)->get()->getRowArray();
            $exam   = $db->table('exams')->where('exID', $exam_id)->get()->getRowArray();

            // Build HTML (avoid huge string concatenation; use buffering)
            $html = '<style>
                body { font-family: sans-serif; font-size: 11pt; }
                table { width: 100%; border-collapse: collapse; margin-top: -12px; }
                th, td { border: 1px solid #444; padding: 5px; text-align: center; }
                th { background-color: #f0f0f0; }
                .student { text-align: left; font-weight: bold; }
            </style>';

            $html .= '<table><thead><tr><th>Index No.</th>';
            foreach ($subjects as $sub) {
                $html .= '<th>' . esc(strtoupper($sub)) . '</th>';
            }
            $html .= '<th>AVRG</th><th>POINTS</th><th>DIV</th><th>RANK</th><th>REMARK</th></tr></thead><tbody>';

            foreach ($results as $r) {
                $decoded = $r['decoded'];
                $subMap = [];
                if (!empty($decoded['subjects']) && is_array($decoded['subjects'])) {
                    $subMap = array_column($decoded['subjects'], null, 'subject');
                }
                $html .= '<tr><td class="student">' . esc($r['student']) . '</td>';
                foreach ($subjects as $sub) {
                    // Correct check: show score if numeric, otherwise blank
                    $score = '';
                    if (isset($subMap[$sub]) && isset($subMap[$sub]['score']) && is_numeric($subMap[$sub]['score'])) {
                        $score = number_format((float)$subMap[$sub]['score'], 0);
                    }
                    $html .= '<td>' . $score . '</td>';
                }
                $avg = $r['crAverage'] ?? '-';
                $points = $decoded['points'] ?? '-';
                $div = $decoded['division'] ?? '-';
                $pos = $r['crPosition'] ?? '-';
                $rem = $decoded['remark'] ?? '-';
                $html .= "<td>{$avg}</td><td>{$points}</td><td>{$div}</td><td>{$pos}</td><td>{$rem}</td></tr>";
            }
            $html .= '</tbody></table>';

            // ========== mPDF creation/safety ==========
            // Clean output buffers to prevent mPDF mixing with previous output
            while (ob_get_level()) { ob_end_clean(); }

            // ensure writable temp dir for mPDF to avoid stalls
            $mpdfConfig = [];
            // optional: set tempDir if default not writable (update path as required)
            $tmp = WRITEPATH . 'tmp';
            if (!is_dir($tmp)) {
                mkdir($tmp, 0755, true);
            }
            $mpdfConfig['tempDir'] = $tmp;

            // instantiate mPDF
            try {
                $mpdf = new \Mpdf\Mpdf(array_merge([
                    'format' => 'A4-L',
                    'margin_top' => 35,
                    'margin_bottom' => 15,
                    'margin_header' => 5,
                    'margin_footer' => 5,
                ], $mpdfConfig));
            } catch (\Mpdf\MpdfException $e) {
                // return error so you can debug
                return redirect()->back()->with('error', 'mPDF init failed: ' . $e->getMessage());
            }

            // Build header safely — use filesystem logo if available to avoid slow URL fetch
            $logoPath = FCPATH . $school['school_logo']; // FCPATH points to document root
            if (!file_exists($logoPath)) {
                // fallback to a small base64 spacer to avoid remote fetch
                $logoHtml = '';
            } else {
                $logoHtml = "<img src='{$logoPath}' height='50' style='vertical-align:middle; margin-right:10px;'>";
            }

            $schoolName = esc($school['school_name']);
            $examName = esc($exam['exName'] ?? 'Exam');
            $className = esc($class['named'] ?? 'Class');
            //NEW EDITION START HERE 
            $data['info'] = $this->examModel->info($exam_id);
            $filename = $data['info']['exam'].' '.$data['info']['class'].'('.$data['info']['year'].') REPORT';
            $logoLeft = FCPATH . 'images/tz_arm.jpeg';   // coat of arms
            $logoRight = FCPATH . 'images/logo.jpg';    // school logo
            $header1 = "
                <div style='border-bottom:1px solid #000; margin-top:1px;'>
                <table width='100%' style='border-collapse:collapse; border:none;'>
                    <tr>
                        <td width='20%' style='text-align:left; border:none;'>
                            <img src='{$logoLeft}' height='100' width='100'>
                        </td>
                        <td width='60%' style='text-align:center; font-weight:bold; border:none;'>
                            <div style='font-size:12px;'>
                                OFISI YA WAZIRI MKUU,<br>
                                TAWALA ZA MIKOA NA SERIKALI ZA MITAA
                            </div>
                            <div style='font-size:12px; font-style: italic;'>
                                HALMASHAURI YA WILAYA YA BUKOBA<br>
                                SHULE YA SEKONDARI LYAMAHORO
                            </div>
                            <div style='font-size:12px; margin-top:5px;'>
                                ".strtoupper($filename)."
                            </div>
                        </td>
                        <td width='20%' style='text-align:right; border:none;'>
                            <img src='{$logoRight}' height='100' width='100'>
                        </td>
                    </tr>
                </table>
                </div><br>
                ";
            //NEW EDITION END HERE
            $headerHTML = "
                <div style='text-align:center; font-weight:bold;'>
                    {$logoHtml}
                    <span style='font-size:18px;'>{$schoolName}</span><br>
                    <span style='font-size:12px;'>{$examName} | {$className} </span>
                </div><hr>
            ";

            $footerHTML = "
                <hr>
                <div style='text-align:justify; font-size:10px;'>
                    Printed on ".date('d M Y H:i')." \tPage <strong>{PAGENO}</strong> of <strong>{nb}</strong>
                </div>
            ";

            $mpdf->SetHTMLHeader($header1);
            $mpdf->SetHTMLFooter($footerHTML);

            // Write and output
            try {
                $mpdf->WriteHTML($html);
                $mpdf->Output("{$examName}_{$className}_Results_{$mode}.pdf", 'D'); // inline
                exit; // ensure script stops after sending pdf
            } catch (\Mpdf\MpdfException $e) {
                return redirect()->back()->with('error', 'mPDF render failed: ' . $e->getMessage());
            }
        } //End of export_class_results_pdf()
        public function report_card($exam_id, $student_id){
            $db = \Config\Database::connect();
            $compiled = $this->compiledResultsModel->getResultByStudent($student_id, $exam_id);
            if (!$compiled) {
                return redirect()->back()->with('error', 'No compiled results found.');
            }
            $decoded = json_decode($compiled['crResults'], true);
            $student = $db->table('students s')
                ->select('concat(stuFname," ",stuMname," ",stuSurname) as name, st.sName as stream, stream_id')
                ->join('streams st', 'st.sid = s.stream_id')
                ->where('stuID', $student_id)
                ->get()->getRowArray();
            $exam  = $db->table('exams')->where('exID', $exam_id)->get()->getRowArray();
            $class = $db->table('classes c')
                        ->join('streams st', 'c.cid=st.class_id')
                        ->where('st.sid', $student['stream_id'] ?? 0)->get()->getRowArray();
            return view('admin/exams/report_card', [
                'decoded'     => $decoded,
                'student'     => $student,
                'exam'        => $exam,
                'class'       => $class,
                'student_id'  => $student_id,
                'exam_id'     => $exam_id,
            ]);
        }
        public function generate_report_card($exam_id, $class_id){
            if (isset($_POST['report'])) {
                $headInstructions = $this->request->getPost('mapendekezo');
                $opdate = $this->request->getPost('openDate');
                $cldate = $this->request->getPost('closeDate');
                $this->report_cards_class($exam_id, $class_id, $headInstructions, $cldate, $opdate);
            }else{
                $data = ['pageTitle'=>'Generate Examination Report Cards', 'exam'=>$exam_id, 'class'=>$class_id];
                return view('admin/exams/get_class_result_card', $data);
            }               
        }
        /*public function report_cards_class($exam_id, $class_id, $Ins=' ', $op=null, $cl=null){
            //Query Data
            $db = \Config\Database::connect();
            $user = session()->get('user_name');
            $data['info'] = $this->examModel->info($exam_id);
            $year   = $this->examModel->getAcadYear($exam_id); // '2025 '2025/2026'
            // Fetch compiled results for the class
            $results = $this->compiledResultsModel->getResultByClass($class_id, $exam_id);

            if (empty($results)) {
                return redirect()->back()->with('error', 'No compiled results found for this class.');
            }

            // Load school, class, exam info
            $school = $db->table('settings')->get()->getRowArray() 
                      ?? ['school_name'=>'School', 'school_logo'=>'uploads/lss_logo.png'];

            $class  = $db->table('classes')->where('cid', $class_id)->get()->getRowArray();
            $exam   = $db->table('exams')->where('exID', $exam_id)->get()->getRowArray();

            // Start PDF HTML
            $html = '<style>
                body { font-family: sans-serif; font-size: 11pt; }
                .title { text-align:center; font-weight:bold; font-size:12pt; margin-bottom:1px; }
                .small { text-align:center; font-size:11pt; }
                table { border-collapse: collapse; width:100%; margin-top:10px; }
                th, td { border:1px solid #333; padding:5px; font-size:10pt; }
                th { background:#eee; }
                .section-title { font-weight:bold; margin-top:10px; }
                .label { font-weight:bold; }
            </style>';
            $filename = $data['info']['exam'].' '.$data['info']['class'].'('.$data['info']['year'].') REPORT';

            $logoLeft = FCPATH . 'images/tz_arm.jpeg';   // coat of arms
            $logoRight = FCPATH . 'images/logo.jpg';    // school logo

            $header = "
                <div style='border-bottom:1px solid #000; margin-top:1px;'>
                <table width='100%' style='border-collapse:collapse; border:none;'>
                    <tr>
                        <td width='20%' style='text-align:left; border:none;'>
                            <img src='{$logoLeft}' height='100' width='100'>
                        </td>

                        <td width='60%' style='text-align:center; font-weight:bold; border:none;'>
                            <div style='font-size:14px;'>
                                OFISI YA WAZIRI MKUU,<br>
                                TAWALA ZA MIKOA NA SERIKALI ZA MITAA
                            </div>
                            <div style='font-size:13px; font-style: italic;'>
                                HALMASHAURI YA WILAYA YA BUKOBA<br>
                                SHULE YA SEKONDARI LYAMAHORO
                            </div>
                            <div style='font-size:12px; margin-top:5px;'>
                                ".strtoupper($filename)."
                            </div>
                        </td>

                        <td width='20%' style='text-align:right; border:none;'>
                            <img src='{$logoRight}' height='100' width='100'>
                        </td>
                    </tr>
                </table>
                </div><br>
                ";
            $footer = "
                <div style='text-align:center; font-size:10px;'>
                    Printed on ".date('d M Y H:i:s')." | Page {PAGENO}/{nb}
                </div>
            ";

            foreach ($results as $r) {
                $decoded = json_decode($r['crResults'], true);
                $mkondo = $this->streamModel->getMe($r['student_id']);
                $studentName = $r['student'];
                $subjects = $decoded['subjects'] ?? [];
                $total = $decoded['total'] ?? 0;
                $points = $decoded['points'] ?? "-";
                $division = $decoded['division'] ?? "-";
                $average = $decoded['average'] ?? "-";
                $remark = $decoded['remark'] ?? "-";
                // --- HEADER ---

                $html .= $header; 
                $html .= '
                <div class="section-title">SEHEMU A: TAARIFA YA MAENDELEO YA MWANAFUNZI</div>
                <table>
                    <tr style="border: none;">
                        <td style="border: none;" colspan="2">Jina: <u><strong>'.esc(strtoupper($studentName)).'</strong></u></td>
                        <td style="border: none;" colspan="1">Kidato: <u><strong>'.esc(strtoupper($this->classInSwahili($class['named']))).'</strong></u></td>
                        <td style="border: none;" colspan="1">Tahasusi/Mkondo: '.esc($mkondo).'</td>
                    </tr>
                </table>';

                // --- SUBJECT TABLE ---
                $html .= '
                <table>
                    <thead>
                        <tr>
                            <th>Somo</th>
                            <th>Alama</th>
                            <th>Daraja</th>
                            <th>Maoni</th>
                            <th>Saini</th>
                        </tr>
                    </thead>
                    <tbody>';
                $count =count($subjects);
                foreach ($subjects as $sub) {
                    if (is_numeric($sub['score'])) {
                        $sc = number_format($sub['score'], 0);
                    }else{
                        $sc = $sub['score'];
                    }
                    $html .= '
                        <tr>
                            <td>'.esc($sub['subject']).'</td>
                            <td style="text-align: center;">'.esc($sc).'</td>
                            <td style="text-align: center;">'.esc($sub['grade']).'</td>
                            <td>'.esc($this->remark($sub['grade'])).'</td>
                            <td></td>
                        </tr>';
                }

                $html .= '
                    <tr>
                        <th>Jumla</th>
                        <td colspan="2">'.$total.' Kati ya '.($count*100).'</td>
                        <th> Alama</th>
                        <td >'.esc($points).'</td>                        
                    </tr>
                    <tr>
                        <th>Wastani</th>
                        <td colspan="2">'.$average.' Kati ya 100</td>
                        <th>Daraja</th>
                        <td >'.esc($division).'</td>
                    </tr>
                </tbody></table>';

                // --- FOOTER SIGNATURE AREA ---
                $html .= '
                <div class="section-title">SEHEMU B: MAELEKEZO KWA MZAZI</div>
                <div>Shule imefungwa leo tarehe '.$cl.' na itafunguliwa tarehe '. $op .'</div>
                <br>
                <div><strong>Maoni ya Mwalimu wa Darasa:</strong> <u>'.$this->getClassMasterComment($total, ($count*100)).'</u></div>
                <div class="label">Jina:______________________________ Saini: _____________________</div>
                <div > <strong>Maoni ya M/Shule </strong><u>'.$this->getHeadComment($total, ($count*100)).'</u> <strong>Maelekezo ya M/Shule:</strong> <u>'.$Ins.'</u> </div>
                <div class="">Jina:<strong><u> FLORENTINA B. BAYYO </u></strong> Saini:______________________</div>
                <br>
                <div class="section-title">SEHEMU C: MAONI NA MAPENDEKEZO YA MZAZI/MLEZI</div>
                <div class="label">(Kipande hiki kikatwe na kulejeshwa Shuleni siku ya kufungu) _____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________ _____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________</div>
                <div class="">Jina:<strong>___________________________________ Simu:____________________________</strong>Saini:_______________________</div>

                <pagebreak />'; // new page for next student
            }

            // MPDF SETUP
            while (ob_get_level()) { ob_end_clean(); }
            $tmp = WRITEPATH.'tmp';
            if (!is_dir($tmp)) mkdir($tmp, 0755, true);
            //| Page {PAGENO} of {nb}
            $mpdf = new \Mpdf\Mpdf(['format'=>'A4','tempDir'=>$tmp,'margin_top'=>10]);
            $mpdf->SetHTMLFooter('<div style="text-align:center; font-size:9pt;">Printed by <strong>'.$user.'</strong>  at <strong>'.date('h:i:sA l d M, Y') .'</strong></div>');
            $mpdf->WriteHTML($html);

            $mpdf->Output($exam['exName'].'Results Report cards_'.$class_id.'.pdf', 'D');
            exit;
        }*/
public function report_cards_class($exam_id, $class_id, $Ins=' ', $op=null, $cl=null){
    $db = \Config\Database::connect();
    $user = session()->get('user_name');
    $info = $this->examModel->info($exam_id);
    $results = $this->compiledResultsModel->getResultByClass($class_id, $exam_id);
    if (empty($results)) {
        return redirect()->back()->with('error', 'No compiled results found for this class.');
    }
    $school = $db->table('settings')->get()->getRowArray();
    $class  = $db->table('classes')->where('cid', $class_id)->get()->getRowArray();
    $exam   = $db->table('exams')->where('exID', $exam_id)->get()->getRowArray();

    $filename = $info['exam'].' '.$info['class'].'('.$info['year'].') REPORT';

    $logoLeft  = FCPATH . 'images/tz_arm.jpeg';
    $logoRight = FCPATH . 'images/logo.jpg';

    $style = '
            <style>
                body { font-family: sans-serif;  font-size: 11pt;   }
                table { border-collapse: collapse;  width: 100%;  }
                th, td { border: 1px solid #333; padding: 5px;  font-size: 10pt; }
                th { background: #eee;   }
                .section-title {font-weight: bold;   margin-top: 10px;  margin-bottom: 5px;      }
                .label { font-weight: bold;  }
            </style>';

    $tmp = WRITEPATH . 'tmp';
    if (!is_dir($tmp)) {
        mkdir($tmp, 0755, true);
    }

    while (ob_get_level()) {
        ob_end_clean();
    }

    $mpdf = new \Mpdf\Mpdf([
        'format'    => 'A4',
        'tempDir'   => $tmp,
        'margin_top' => 10,
        'margin_left' => 10,
        'margin_right' => 10,
    ]);

    $mpdf->SetHTMLFooter(
        '<div style="text-align:center; font-size:9pt;">
            Printed on <strong>'.date('h:i:s A l d M, Y').'</strong>
        </div>'
    );

    // CSS only once
    $mpdf->WriteHTML($style, \Mpdf\HTMLParserMode::HEADER_CSS);

    $totalStudents = count($results);
    $current = 0;

    foreach ($results as $r) {
        $current++;
        $decoded = json_decode($r['crResults'], true);
        $mkondo = $this->streamModel->getMe($r['student_id']);
        $studentName = $r['student'];
        $subjects  = $decoded['subjects'] ?? [];
        $total     = $decoded['total'] ?? 0;
        $points    = $decoded['points'] ?? '-';
        $division  = $decoded['division'] ?? '-';
        $average   = $decoded['average'] ?? '-';
        $html = '';
        // HEADER
        $html .= "
        <div style='border-bottom:1px solid #000; margin-bottom:10px;'>
            <table style='border:none;'>
                <tr>
                    <td width='20%' style='border:none;text-align:left;'>
                        <img src='{$logoLeft}' width='90'>
                    </td>
                    <td width='60%' style='border:none;text-align:center;'>
                        <div style='font-size:13px;font-weight:bold;'>
                            OFISI YA WAZIRI MKUU,<br>
                            TAWALA ZA MIKOA NA SERIKALI ZA MITAA
                        </div>
                        <div style='font-size:12px;font-style:italic;'>
                            HALMASHAURI YA WILAYA YA BUKOBA<br>
                            SHULE YA SEKONDARI LYAMAHORO
                        </div>
                        <div style='font-size:12px;margin-top:5px;font-weight:bold;'>
                            ".strtoupper($filename)."
                        </div>
                    </td>
                    <td width='20%' style='border:none;text-align:right;'>
                        <img src='{$logoRight}' width='90'>
                    </td>
                </tr>
            </table>
        </div>
        ";

        // STUDENT INFO
        $html .= '
        <div class="section-title">
            SEHEMU A: TAARIFA YA MAENDELEO YA MWANAFUNZI
        </div>
        <table>
            <tr>
                <td colspan="2" style="border:none;">
                    Jina:
                    <strong>'.strtoupper(esc($studentName)).'</strong>
                </td>
                <td style="border:none;">
                    Kidato:
                    <strong>'.strtoupper($this->classInSwahili($class['named'])).'</strong>
                </td>
                <td style="border:none;">
                    Tahasusi/Mkondo:
                    <strong>'.esc($mkondo).'</strong>
                </td>
            </tr>
        </table>';

        // SUBJECTS
        $html .= '
        <table>
            <thead>
                <tr>
                    <th>Somo</th>
                    <th>Alama</th>
                    <th>Daraja</th>
                    <th>Maoni</th>
                    <th>Saini</th>
                </tr>
            </thead>
            <tbody>';

        $count = count($subjects);
        foreach ($subjects as $sub) {
            $score = is_numeric($sub['score'])
                ? number_format($sub['score'], 0)
                : $sub['score'];
            $subname = $this->subjectModel->getName($sub['subject']);
            $html .= '
            <tr>
                <td>'.esc($subname).'</td>
                <td align="center">'.$score.'</td>
                <td align="center">'.esc($sub['grade']).'</td>
                <td>'.$this->remark($sub['grade']).'</td>
                <td></td>
            </tr>';
        }

        $html .= '
            <tr>
                <th>Jumla</th>
                <td colspan="2">'.$total.' kati ya '.($count * 100).'</td>
                <th>Alama</th>
                <td>'.$points.'</td>
            </tr>
            <tr>
                <th>Wastani</th>
                <td colspan="2">'.$average.'</td>
                <th>Daraja</th>
                <td>'.$division.'</td>
            </tr>
            </tbody>
        </table>';
        // COMMENTS
        $html .= '
        <div class="section-title">
            SEHEMU B: MAELEKEZO KWA MZAZI
            <p>Shule imefungwa leo tarehe '.$cl.' na itafunguliwa tarehe '.$op.'  </p>
        </div>
        <p>
            <strong>Maoni ya Mwalimu wa Darasa:</strong>
            '.$this->getClassMasterComment($total, ($count * 100)).'
        </p>
        <p>Jina _______________________        Saini _______________________      </p>

        <p>
            <strong>Maoni ya M/Shule:</strong>
            '.$this->getHeadComment($total, ($count * 100)).'    
            <strong>Maelekezo ya M/Shule:</strong>
            '.$Ins.'
        </p>
        <p>
            Jina:
            <strong>FLORENTINA B. BAYYO</strong>
            &nbsp;&nbsp;&nbsp;
            Saini _______________________
        </p>

        <div class="section-title">
            SEHEMU C: MAONI YA MZAZI/MLEZI
        </div>
        <div class="label">(Kipande hiki kikatwe na kurejeshwa Shuleni siku ya kufungua) _____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________</div>
        <p>
            Jina _______________________
            Simu _______________________
            Saini _______________________
        </p>';

        // Write one student at a time
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        // Add page except last student
        if ($current < $totalStudents) {
            $mpdf->AddPage();
        }
    }

    $mpdf->Output(
        $exam['exName'].'_Report_Cards_'.$class_id.'.pdf',
        'D'
    );

    exit;
}
        public function saveAcademicYear(){
            $validation = \Config\Services::validation();

            $validation->setRules([
                'ayName'     => 'required|min_length[4]',
                'level'      => 'required',
                'start_date' => 'required|valid_date',
                'end_date'   => 'required|valid_date',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => $validation->listErrors()
                ]);
            }

            /** Post data */
            $data = [
                'ayName'     => $this->request->getPost('ayName'),
                'level'      => $this->request->getPost('level'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date'   => $this->request->getPost('end_date'),
            ];

            /** Use custom ID generator */
            if ($this->ayModel->saveAy($data)) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Academic Year added successfully'
                ]);
            }

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to save Academic Year'
            ]);
        }

    /*===================================================================
            TRANSPORTATION MODULE
    =======================================================================*/
    public function add_route(){
        $data = [
            'pageTitle' => 'Add Route',            
            'regions' => $this->regionModel->getOrdered(),
            'activeRoutes' => $this->routeModel->getWithStatus()
        ];
        return view('admin/tours/add_routes', $data);
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
    public function edit_route($route_id){
        $data = [
            'pageTitle' => 'Update Route Info',
            'route' =>$this->routeModel->getIt($route_id),
            'regions' =>$this->regionModel->findAll(),
            'recStations' =>$this->stationModel->getAll(),
            'assignedStations' =>$this->routeStationModel->getRouteStations($route_id)
        ];
        return view('admin/tours/edit_route', $data);
    }
    public function save_stationOnRoute($route_id){
        $data = [
            'station' => $this->request->getPost('station'),
            'status' => 'Active',
            'route' => $route_id
        ];
        $result = $this->routeStationModel->add($data);
        if(!$result){
            return redirect()->back()->with('error', 'Something Went Wrong, Did not Saved');
        }
        $name = $this->request->getPost('name');
        return redirect()->back()->with('success', 'Assigned Successfully');
    }
    public function tours(){
        $data['studentRoutes'] = $this->studentRouteStationModel->getAllAssignments();
        $data['tourRoutes'] = $this->tourRouteModel->getFullAssignments();
        $data['routes'] = $this->routeModel->findAll();
        $data['tours'] = $this->tourModel->findAll();
        $data['employees'] = $this->employeeModel->activeEmployee();
        $data['vehicles'] = $this->vehicleModel->getAll();
        return view('admin/tours/index',$data);
    }
    public function add_tour(){
        $data = [
            'tour'     => $this->request->getPost('tour'), 
            'category' => $this->request->getPost('category'),
            'start'    => $this->request->getPost('start'),
            'end'      => $this->request->getPost('end'),
            'status'   => 'Active'
        ];
        if($this->tourModel->add($data)){
          return redirect()->back()->with('success', 'Added Successfully');
      }else{
        return redirect()->back()->with('error', 'Failed to Add!');
      }
        
    } //End of adding route form
    public function assignTourOnRoute(){
        $data['tour'] = $this->request->getPost('tour');
        $data['route'] = $this->request->getPost('route');
        $data['employee'] = $this->request->getPost('employee');
        $data['vehicle'] = $this->request->getPost('vehicle');
        if ($this->tourRouteModel->add($data)) {
            return redirect()->back()->with('success', 'Successed Assignment');
        }else{
            return redirect()->back()->with('error', 'Assignment Failed');
        }
    }
    public function config(){
        $data = [
            'classes' => $this->classModel->findAll(),
            'routes'  => $this->routeModel->findAll(),
            'tours'   => $this->tourModel->findAll()
        ];
        return view('admin/tours/config', $data);
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
        return view('admin/tours/add_stations', $data);
    }
    public function save_station(){
        $stations = $this->request->getPost('station');  // array
        $streets  = $this->request->getPost('street');   // array

        if (empty($stations)) {
            return redirect()->back()->with('error', 'No station submitted!');
        }
        foreach ($stations as $i => $sta) {
            // Skip empty rows
            if (trim($sta) == "") {
                continue;
            }
            $data = [
                'staName'     => $sta,
                'staGps'      => null,
                'staBefore'   => null,
                'staAfter'    => null,
                'street_id'   => $streets[$i] ?? null,
                'staCreated'  => date('Y-m-d H:i:s'),
                'staStatus'   => 'active',
            ];
            $this->stationModel->insert($data);
        }
        return redirect()->back()->with('success', 'Stations recorded successfully!');
    }//End of saving station
    public function studentOnRoute(){
        $data['assignments'] = $this->studentRouteStationModel->getAllAssignments();
        $data['pageTitle'] = 'Allocated Students';
        return view('admin/tours/views_students_on_route', $data);
    }
    public function assignRoute($class = 0){
        if ($class === 0) {
            $data = [
                'pageTitle' => 'Students\' Allocation  on Route\'s Stations ',
                'classes' => $this ->classModel ->findAll(),
                'assignedStudents' => $this->studentModel->getWithStudentRoute()
            ];
            return view('admin/tours/manage', $data);
        }else{
            $data = [
                'pageTitle' => 'Students\' Allocation  on Route\'s Stations ',
                'classes' => $this ->classModel ->findAll(),
                'assignedStudents' => $this->studentModel->getFullDetailsByClass($class)
            ];
            return view('admin/tours/manage', $data);
        }        
    }
    // Save assigned students
    /** Save assignments submitted from form */
    public function saveAssignedStudent(){
        $assignments = $this->request->getPost('assignments');
        $user_id = session()->get('user_id');

        if (!$assignments) {
            return redirect()->back()->with('error','No data submitted.');
        }

        foreach($assignments as $assign){
            if(empty($assign['student_id']) || empty($assign['route_station_id'])) continue;

            $this->studentRouteStationModel->insert([
                'student_id'       => $assign['student_id'],
                'route_station_id' => $assign['route_station_id'],
                'assigned_at'      => date('Y-m-d H:i:s'),
                'assigned_by'      => $user_id,
                'status'           => 'active'
            ]);
        }

        return redirect()->back()->with('success','Assignments saved successfully!');
    }

    /** Print report based on filters */
    public function report(){
        $class_id = $this->request->getGet('class_id');
        $route_id = $this->request->getGet('route_id');
        $tour_id  = $this->request->getGet('tour_id');

        $builder = $this->studentRouteStationModel
            ->select('students.stuID, CONCAT(stuFname," ",COALESCE(stuMname,"")," ",stuSurname) AS name, classes.named AS class, routes.rouName AS route, tours.touName AS tour, stations.staName AS station')
            ->join('students','students.stuID = student_routestation.student_id')
            ->join('route_stations','route_stations.rsID = student_routestation.route_station_id')
            ->join('stations','stations.staID = route_stations.station_id')
            ->join('routes','routes.rouID = route_stations.route_id')
            ->join('tour_routes','tour_routes.route_id = routes.rouID')
            ->join('tours','tours.touID = tour_routes.tour_id')
            ->join('streams','streams.sid = students.stream_id')
            ->join('classes','classes.cid = streams.class_id');

        if($class_id) $builder->where('classes.cid',$class_id);
        if($route_id) $builder->where('routes.rouID',$route_id);
        if($tour_id) $builder->where('tours.touID',$tour_id);

        $data['assignments'] = $builder->findAll();

        return view('admin/trans/assignment_report', $data);
    }
    public function vehicles($value='') {
        $data = [
            'pageTitle' => 'Vehicles',
            'regions' =>$this->regionModel->findAll(),
            'districts' =>$this->districtModel->getAll(),
            'wards' =>$this->wardModel->getAll(),
            'streets' =>$this->streetModel->getAll(),
            'recStations' =>$this->stationModel->getAll(),
            'vehicles' =>$this->vehicleModel->getAll()
        ];
        return view('admin/tours/add_vehicles', $data);
    }
    public function store_vehicle(){
        $this->vehicleModel->save([
            'vePlateNumber' => $this->request->getPost('vePlateNumber'),
            'veModel' => $this->request->getPost('veModel'),
            'veOwnership' => $this->request->getPost('veOwnership'),
            'veNamed' => $this->request->getPost('veNamed'),
        ]);
        $vhc = $this->request->getPost('vePlateNumber').' '.$this->request->getPost('veModel');
        return redirect()->back()->with('success', $vhc.' Added Successfully');
    }










    public function register(){
        return view('auth/register');
    } //End of Self register

    
    public function del_route($value=''){
        $route = $this->request->getGet('del_id');
        if ($this->routeModel->delete($route)) {
            return redirect()->back()->with('delete', 'Deleted successfully');
        }else{
            return redirect()->back()->with('undelete', 'Something Went Wrong! Failed to Delete');
        }
    }

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
    
    public function employee(){
        $data  = [
            'employees' => $this->employeeModel->activeEmployee(),
        ];
        return view('employee/index', $data);
    }

    
    public function logout(){
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'You have been logged out.');
    } //End of Logout

    /*===============================================================================
        HR MODULE
    ================================================================================*/
    public function salaryGrades(){
        $data['grades'] = $this->salaryGradeModel->findAll();
        return view('admin/staff/salary_grades', $data);
    }

    public function saveSalaryGrade() {
        $this->salaryGradeModel->save([
            'grade_name' => $this->request->getPost('grade_name'),
            'basic_salary' => $this->request->getPost('basic_salary'),
            'housing_allowance' => $this->request->getPost('housing_allowance'),
            'transport_allowance' => $this->request->getPost('transport_allowance'),
            'medical_allowance' => $this->request->getPost('medical_allowance'),
            'other_allowance' => $this->request->getPost('other_allowance'),
        ]);

        return redirect()->back()->with('success', 'Salary Grade Saved Successfully');
    }

    public function payrollSetup(){
        $employeeModel = new \App\Models\EmployeeModel();
        $gradeModel    = new \App\Models\SalaryGradeModel();

        $employees = $employeeModel
            ->select('employees.*, sg.grade_name, sg.basic_salary, sg.transport_allowance, sg.housing_allowance')
            ->join('salary_grades sg', 'sg.grade_id = employees.empSalaryGradeID', 'left')
            ->where('empStatus', 'Active')
            ->findAll();

        $data = [
            'pageTitle' => 'Payroll Setup',
            'employees' => $employees,
        ];

        return view('admin/staff/payroll', $data);
    }

    public function processPayroll(){
        $month = $this->request->getPost('month');
        $year  = $this->request->getPost('year');

        $db = \Config\Database::connect();

        // Check if payroll already exists
        $existing = $db->table('payroll_runs')
            ->where('payroll_month', $month)
            ->where('payroll_year', $year)
            ->get()
            ->getRow();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Payroll already processed for this period.');
        }

        // Create payroll run
        $db->table('payroll_runs')->insert([
            'payroll_month' => $month,
            'payroll_year'  => $year,
            'processed_by'  => session()->get('user_id'),
            'status'        => 'Draft'
        ]);

        $payrollID = $db->insertID();

        // Fetch employees with grades
        $employees = $db->table('employees')
            ->select('employees.*, salary_grades.*')
            ->join('salary_grades', 'salary_grades.grade_id = employees.empSalaryGradeID', 'left')
            ->where('empStatus', 'Active')
            ->get()
            ->getResult();

        foreach ($employees as $emp) {

            $basic     = $emp->basic_salary ?? 0;
            $housing   = $emp->housing_allowance ?? 0;
            $transport = $emp->transport_allowance ?? 0;
            $medical   = $emp->medical_allowance ?? 0;
            $other     = $emp->other_allowance ?? 0;

            $gross = $basic + $transport + $housing;

            // ✅ STEP 1: Insert payroll item FIRST
            $this->payrollItemModel->insert([
                'payroll_id' => $payrollID,
                'employee_id' => $emp->empID,
                'basic_salary' => $basic,
                'housing_allowance' => $housing,
                'transport_allowance' => $transport,
                'medical_allowance' => $medical,
                'other_allowance' => $other,
                'gross_salary' => 0,
                'total_deductions' => 0,
                'net_salary' => 0
            ]);

            $payrollItemID = $this->payrollItemModel->getInsertID();

            // ✅ STEP 2: Process deductions
            $totalDeduction = 0;

            $employeeDeductions = $this->employeeDeductionModel
                ->select('employee_deductions.*, deductions.*')
                ->join('deductions', 'deductions.deductionID = employee_deductions.deductionID')
                ->where('employee_deductions.empID', $emp->empID)
                ->where('employee_deductions.is_active', 1)
                ->where('deductions.is_active', 1)
                ->findAll();

            foreach ($employeeDeductions as $ded) {

                $value = $ded['custom_value'] ?? $ded['deduction_value'];

                if ($ded['deduction_type'] === 'percentage') {

                    if ($ded['applies_to'] === 'gross') {
                        $amount = ($gross * $value) / 100;
                    } else {
                        $amount = ($basic * $value) / 100;
                    }

                } else {
                    $amount = $value;
                }

                $totalDeduction += $amount;

                $this->payrollItemDeductionModel->insert([
                    'payroll_item_id' => $payrollItemID,
                    'deduction_name'  => $ded['deduction_name'],
                    'amount'          => $amount
                ]);
            }

            // ✅ STEP 3: Calculate Net
            $net = $gross - $totalDeduction;

            // ✅ STEP 4: Update payroll item with totals
            $this->payrollItemModel->update($payrollItemID, [
                'gross_salary' => $gross,
                'total_deductions' => $totalDeduction,
                'net_salary' => $net
            ]);
        }


        return redirect()->back()->with('success', 'Payroll processed successfully.');
    }

    public function manageEmployeeDeductions($empID){
        $deductionModel = new \App\Models\DeductionModel();
        $employeeDeductionModel = new \App\Models\EmployeeDeductionModel();

        $allDeductions = $deductionModel
            ->where('is_active', 1)
            ->findAll();

        $assigned = $employeeDeductionModel
            ->where('empID', $empID)
            ->where('is_active', 1)
            ->findAll();

        $assignedIDs = array_column($assigned, 'deductionID');

        return view('admin/staff/setdeductions', [
            'empID' => $empID,
            'allDeductions' => $allDeductions,
            'assignedIDs' => $assignedIDs,
            'assignedData' => $assigned,
            'pageTitle'  =>'Employee\'s Deductions'
        ]);
    }

    public function saveEmployeeDeductions(){
        $empID = $this->request->getPost('empID');
        $selected = $this->request->getPost('deductions') ?? [];
        $custom = $this->request->getPost('custom') ?? [];

        $employeeDeductionModel = new \App\Models\EmployeeDeductionModel();

        // Remove existing
        $employeeDeductionModel->where('empID', $empID)->delete();

        foreach ($selected as $deductionID) {

            $employeeDeductionModel->insert([
                'empID' => $empID,
                'deductionID' => $deductionID,
                'custom_value' => $custom[$deductionID] ?? null,
                'is_active' => 1
            ]);
        }

            $data  = [
                'pageTitle' =>'Manage Employees\' Record',
                'employees' => $this->employeeModel->activeEmployee(),
            ];
            return redirect()->to('admin/staff/manage')->with('success', 'Deductions Saved Successfully');
    }

    public function generatePayroll()
    {
        $month = $this->request->getPost('month');
        $year  = $this->request->getPost('year');

        // Prevent duplicate payroll
        $exists = $this->payrollRunModel
            ->where('payroll_month', $month)
            ->where('payroll_year', $year)
            ->first();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Payroll already exists for this period.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Create payroll run
        $this->payrollRunModel->insert([
            'payroll_month' => $month,
            'payroll_year'  => $year,
            'status'        => 'Draft',
            'processed_by'  => session()->get('userID')
        ]);

        $payrollID = $this->payrollRunModel->getInsertID();

        // Fetch employees with salary grade
        $employees = $this->employeeModel
            ->select('employees.*, salary_grades.*')
            ->join('salary_grades', 'salary_grades.grade_id = employees.empSalaryGradeID', 'left')
            ->findAll();

        foreach ($employees as $emp) {

            if (!$emp['empSalaryGradeID']) {
                continue;
            }

            $basic     = $emp['basic_salary'] ?? 0;
            $housing   = $emp['housing_allowance'] ?? 0;
            $transport = $emp['transport_allowance'] ?? 0;
            $medical   = $emp['medical_allowance'] ?? 0;
            $other     = $emp['other_allowance'] ?? 0;

            $gross = $basic + $housing + $transport + $medical + $other;

            // Insert payroll item snapshot
            $this->payrollItemModel->insert([
                'payroll_id' => $payrollID,
                'employee_id' => $emp['empID'],
                'basic_salary' => $basic,
                'housing_allowance' => $housing,
                'transport_allowance' => $transport,
                'medical_allowance' => $medical,
                'other_allowance' => $other,
                'gross_salary' => 0,
                'total_deductions' => 0,
                'net_salary' => 0
            ]);

            $itemID = $this->payrollItemModel->getInsertID();

            // Get employee deductions
            $employeeDeductions = $this->employeeDeductionModel
                ->select('employee_deductions.*, deductions.*')
                ->join('deductions', 'deductions.deductionID = employee_deductions.deductionID')
                ->where('employee_deductions.empID', $emp['empID'])
                ->where('employee_deductions.is_active', 1)
                ->where('deductions.is_active', 1)
                ->findAll();

            $totalDeduction = 0;

            foreach ($employeeDeductions as $ded) {

                $value = $ded['custom_value'] ?? $ded['deduction_value'];

                if ($ded['deduction_type'] === 'percentage') {

                    if ($ded['applies_to'] === 'gross') {
                        $amount = ($gross * $value) / 100;
                    } else {
                        $amount = ($basic * $value) / 100;
                    }

                } else {
                    $amount = $value;
                }

                $totalDeduction += $amount;

                $this->payrollItemDeductionModel->insert([
                    'payroll_item_id' => $itemID,
                    'deduction_name'  => $ded['deduction_name'],
                    'amount'          => $amount
                ]);
            }

            $net = $gross - $totalDeduction;

            // Update payroll item totals
            $this->payrollItemModel->update($itemID, [
                'gross_salary' => $gross,
                'total_deductions' => $totalDeduction,
                'net_salary' => $net
            ]);
        }

        $db->transComplete();

        return redirect()->to('admin/payroll/list')
            ->with('success', 'Payroll generated successfully.');
    }

    public function payrollList(){
        $data['payrolls'] = $this->payrollRunModel
            ->orderBy('payroll_year', 'DESC')
            ->orderBy('payroll_month', 'DESC')
            ->findAll();

        return view('admin/staff/payroll_list', $data);
    }

    public function viewPayroll($payrollID){
        $data['payroll'] = $this->payrollRunModel->find($payrollID);

        $data['items'] = $this->payrollItemModel->getItems($payrollID);

        return view('admin/staff/payroll_views', $data);
    }

    public function payslip($itemID) {
        $data['item'] = $this->payrollItemModel
            ->select('payroll_items.*, employees.*, payroll_runs.payroll_month, payroll_runs.payroll_year')
            ->join('employees', 'employees.empID = payroll_items.employee_id')
            ->join('payroll_runs', 'payroll_runs.payroll_id = payroll_items.payroll_id')
            ->where('item_id', $itemID)
            ->first();

        $data['deductions'] = $this->payrollItemDeductionModel
            ->where('payroll_item_id', $itemID)
            ->findAll();

        return view('admin/staff/payslip_views', $data);
    }

    public function approvePayroll($payrollID){
        $this->payrollRunModel->update($payrollID, [
            'status' => 'Approved'
        ]);

        return redirect()->back()->with('success', 'Payroll approved.');
    }

    public function markPayrollPaid($payrollID){
        $this->payrollRunModel->update($payrollID, [
            'status' => 'Paid'
        ]);

        return redirect()->back()->with('success', 'Payroll marked as Paid.');
    }

    public function deletePayroll($payrollID) {
        $payroll = $this->payrollRunModel->find($payrollID);

        if ($payroll['status'] !== 'Draft') {
            return redirect()->back()
                ->with('error', 'Only Draft payroll can be deleted.');
        }

        $this->payrollRunModel->delete($payrollID);

        return redirect()->back()->with('success', 'Payroll deleted.');
    }

    public function payrollReports(){
        $month = $this->request->getGet('month');
        $year  = $this->request->getGet('year');

        $data = [];

        if ($month && $year) {

            $payroll = $this->payrollRunModel
                ->where('payroll_month', $month)
                ->where('payroll_year', $year)
                ->first();

            if ($payroll) {

                $items = $this->payrollItemModel
                    ->where('payroll_id', $payroll['payroll_id'])
                    ->findAll();

                $totalGross = 0;
                $totalDeduction = 0;
                $totalNet = 0;

                foreach ($items as $item) {
                    $totalGross += $item['gross_salary'];
                    $totalDeduction += $item['total_deductions'];
                    $totalNet += $item['net_salary'];
                }

                // Deduction breakdown
                $deductions = $this->payrollItemDeductionModel
                    ->select('deduction_name, SUM(amount) as total_amount')
                    ->join('payroll_items', 'payroll_items.item_id = payroll_item_deductions.payroll_item_id')
                    ->where('payroll_items.payroll_id', $payroll['payroll_id'])
                    ->groupBy('deduction_name')
                    ->findAll();

                $data = [
                    'payroll' => $payroll,
                    'totalGross' => $totalGross,
                    'totalDeduction' => $totalDeduction,
                    'totalNet' => $totalNet,
                    'deductions' => $deductions
                ];
            } else {
                session()->setFlashdata('error', 'No payroll found for that period.');
            }
        }

        return view('admin/staff/payroll_reports', $data);
    }

/*====================================================================================
    ATTENDANCE OF STUDENTS AT SCHOOL
=====================================================================================*/

    public function recordAttendance($stream_id)  {
        $data['students'] = $this->studentModel->getByStream($stream_id);
        $data['stream_id'] = $stream_id;
        $data['pageTitle'] = 'Students\' Attendance';

        return view('admin/students/attendance_form', $data);
    }

    /**
     * Save attendance
     */
    public function saveAttendance(){
        $students = $this->request->getPost('students');
        $date = $this->request->getPost('date');
        $session = $this->request->getPost('session');
        $term_id = $this->request->getPost('term_id');
        $stream_id = $this->request->getPost('stream_id');

        $data = [];

        foreach ($students as $student_id => $status) {

            $data[] = [
                'student_id' => $student_id,
                'stream_id'  => $stream_id,
                'session'    => $session,
                'date'       => $date,
                'term_id'    => $term_id,
                'status'     => $status
            ];
        }

        // Save (auto handles duplicates)
        $this->attendanceModel->saveBatchAttendance($data);

        return redirect()->back()->with('success', 'Attendance saved successfully');
    }
    public function attendanceHistory(){
        $stream_id = $this->request->getGet('stream_id');
        $month = $this->request->getGet('month'); // format: 2026-04
        $session = $this->request->getGet('session');

        // Get students
        $students = $this->studentModel->getByStream($stream_id);

        // Get all attendance for that month
        $attendance = $this->attendanceModel
            ->where('stream_id', $stream_id)
            ->where('session', $session)
            ->like('date', $month, 'after')
            ->findAll();

        //Transform into matrix
        $attendanceMap = [];
        foreach ($attendance as $row) {
            $day = date('j', strtotime($row['date'])); // 1–31
            $attendanceMap[$row['student_id']][$day] = $row['status'];
        }
        $data = [
            'students' => $students,
            'attendanceMap' => $attendanceMap,
            'month' => $month,
            'session' => $session
        ];
        return view('admin/students/attendance_history', $data);
    }
}