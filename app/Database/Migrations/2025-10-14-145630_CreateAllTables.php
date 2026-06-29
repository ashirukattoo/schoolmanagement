<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllTables extends Migration{
    public function up(){
        // Regions
        $this->forge->addField([
            'regID'     => ['type' => 'INT', 'auto_increment' => true],
            'regName'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'regZone'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'regLand'   => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('regID', true);
        $this->forge->addKey(['regName', 'regZone'], false, true, 'region');
        $this->forge->createTable('regions');

        //  Districts
        $this->forge->addField([
            'disID'     => ['type' => 'INT', 'auto_increment' => true],
            'disName'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'region_id' => ['type' => 'INT'],
        ]);
        $this->forge->addKey('disID', true);
        $this->forge->addKey(['disName', 'region_id'], false, true, 'district');
        $this->forge->addForeignKey('region_id', 'regions', 'regID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('districts');

        // Wards
        $this->forge->addField([
            'waID'      => ['type' => 'INT', 'auto_increment' => true],
            'waName'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'district_id' => ['type' => 'INT'],
        ]);
        $this->forge->addKey('waID', true);
        $this->forge->addKey(['waName', 'district_id'], false, true, 'ward');
        $this->forge->addForeignKey('district_id', 'districts', 'disID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('wards');

        // Streets
        $this->forge->addField([
            'strID'     => ['type' => 'INT', 'auto_increment' => true],
            'strName'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'ward_id'   => ['type' => 'INT'],
        ]);
        $this->forge->addKey('strID', true);
        $this->forge->addKey(['strName', 'ward_id'], false, true, 'street');
        $this->forge->addForeignKey('ward_id', 'wards', 'waID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('streets');

        // Routes
        $this->forge->addField([
            'rouID'     => ['type' => 'INT', 'auto_increment' => true],
            'rouName'   => ['type' => 'VARCHAR', 'constraint' => 150],
            'rouStart'  => ['type' => 'VARCHAR', 'constraint' => 150],
            'rouEnd'    => ['type' => 'VARCHAR', 'constraint' => 150],
            'rouCreated'=> ['type' => 'DATETIME', 'null' => true],
            'rouUpdated'=> ['type' => 'DATETIME', 'null' => true],
            'rouStatus' => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('rouID', true);
        $this->forge->createTable('routes');

        //Stations
        $this->forge->addField([
            'staID'      => ['type' => 'INT', 'auto_increment' => true],
            'staName'    => ['type' => 'VARCHAR', 'constraint' => 150],
            'staGps'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'street_id'  => ['type' => 'INT', 'null' => true],
            'staCreated' => ['type' => 'DATETIME', 'null' => true],
            'staUpdated' => ['type' => 'DATETIME', 'null' => true],
            'staStatus'  => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('staID', true);
        $this->forge->addKey(['staName', 'street_id'], false, true, 'station');
        $this->forge->addForeignKey('street_id', 'streets', 'strID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stations');

        // Route_Station
        $this->forge->addField([
            'rsID'      => ['type' => 'INT', 'auto_increment' => true],
            'route_id'  => ['type' => 'INT'],
            'station_id'=> ['type' => 'INT'],
            'station_before'  => ['type' => 'INT', 'null' => true],
            'station_after'   => ['type' => 'INT', 'null' => true],
            'rsCreated' => ['type' => 'DATETIME', 'null' => true],
            'rsStatus'  => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('rsID', true);
        $this->forge->addKey(['route_id', 'station_id'], false, true, 'route_station');
        $this->forge->addForeignKey('route_id', 'routes', 'rouID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('station_id', 'stations', 'staID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('route_stations');

        // Vehicles
        $this->forge->addField([
            'veID'          => ['type' => 'INT', 'auto_increment' => true],
            'vePlateNumber' => ['type' => 'VARCHAR', 'constraint' => 100],
            'veModel'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'veOwnership'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'veNamed'       => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('veID', true);
        $this->forge->addKey('vePlateNumber', false, true, 'vehicle');
        $this->forge->createTable('vehicles');

        // Guardians
        $this->forge->addField([
            'guID'         => ['type' => 'INT', 'auto_increment' => true],
            'guName'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'guSex'        => ['type' => 'VARCHAR', 'constraint' => 10],
            'guDob'       => ['type' => 'DATE', 'null' => true],
            'guPhone'     => ['type'=>'VARCHAR', 'constraint'=>15, 'null'=>true],
            'guOccupasion' => ['type' => 'VARCHAR', 'constraint' => 100],
            'guEmail'     => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'guPassword'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'guRegisterd'  => ['type' => 'DATETIME', 'null' => true],
            'guStatus'     => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('guID', true);
        $this->forge->createTable('guardians');

        //Classess
        $this->forge->addField([
            'cid'     => ['type'=>'INT', 'auto_increment'=>true],
            'named'   => ['type'=>'VARCHAR', 'constraint'=>50],
            'short'   => ['type'=>'VARCHAR', 'constraint'=>10],
            'numeral' => ['type'=>'TINYINT'],
            'level'   => ['type'=>'INT', 'default'=>1],
            'created' => ['type'=>'DATETIME', 'null'=>true],
            'updated' => ['type'=>'DATETIME', 'null'=>true],
            'status'  => ['type' => 'ENUM', 'constraint'=>['Active', 'Inactive', 'Comming', 'Deleted'], 'default'=>'Active']
        ]);
        $this->forge->addKey('cid', true);
        $this->forge->addKey(['named', 'numeral', 'level'], false, true, 'class_detail');
        $this->forge->createTable('classes');

        //Streams
        $this->forge->addField([
            'sid'      => ['type'=>'INT', 'auto_increment'=>true],
            'class_id' => ['type'=>'INT'],
            'sName'    => ['type'=>'VARCHAR', 'constraint'=>50],
            'sCreated' => ['type'=>'DATETIME', 'null'=>true],
            'sUpdated' => ['type'=>'DATETIME', 'null'=>true],
            'sStatus'  => ['type' => 'ENUM', 'constraint' => ['Active', 'Canceled', 'Next'], 'default'=>'Active']
        ]);
        $this->forge->addKey('sid', true);
        $this->forge->addKey(['class_id', 'sName'], false, true, 'stream');
        $this->forge->addForeignKey('class_id', 'classes', 'cid', 'CASCADE', 'CASCADE');
        $this->forge->createTable('streams');

        //  Students
        $this->forge->addField([
            'stuID'      => ['type' => 'INT', 'auto_increment' => true],
            'stuFname'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'stuMname'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'stuSurname' => ['type' => 'VARCHAR', 'constraint' => 100],
            'stuSex'     => ['type' => 'ENUM', 'constraint' => ['Female', 'Male']],
            'stuDob'     => ['type' => 'DATE', 'null' => true],
            'stream_id'  => ['type' => 'INT'],
            'guardian_id'=> ['type' => 'INT', 'null'=>true],
            'stuStatus'  => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('stuID', true);
        $this->forge->addForeignKey('guardian_id', 'guardians', 'guID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('stream_id', 'streams', 'sid', 'CASCADE', 'CASCADE');
        $this->forge->createTable('students');

        // Employees
        $this->forge->addField([
            'empID'         => ['type' => 'INT', 'auto_increment' => true],
            'empFname'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'empMname'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'empSurname'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'empSex'        => ['type' => 'VARCHAR', 'constraint' => 10],
            'empDob'        => ['type' => 'DATE', 'null' => true],
            'empRole'       => ['type' => 'ENUM', 'constraint' => ['Teacher', 'Staff', 'Technician', 'Specialist'], 'default'=>'Teacher'],
            'empEmail'      => ['type' => 'VARCHAR', 'constraint' => 75, 'unique' => true],
            'empPassword'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'empHired'      => ['type' => 'DATE', 'null' => true],
            'empRegisterd'  => ['type' => 'DATETIME', 'null' => true],
            'empStatus'     => ['type' => 'ENUM', 'constraint' => ['Active', 'Leaved', 'Blocked', 'Added'], 'default'=>'Added'],
        ]);
        $this->forge->addKey('empID', true);
        $this->forge->createTable('employees');

        //Employee Role
        $this->forge->addField([
            'erID' => ['type' => 'INT', 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'null' => false],
            'erRole' => ['type' => 'ENUM', 'constraint' => [ 'Head', 
                                                        'Deputy',
                                                        'Manager',
                                                        'Director',
                                                        'Human Resource',
                                                        'Academic',
                                                        'Accoutant',
                                                        'Discipline',
                                                        'Class Master',
                                                        'Subject Master',
                                                        'Secretary',
                                                        'Labtechnician',
                                                        'Specific Task',
                                                        'Admin',
                                                        'Cancellor',
                                                        'Assets and Value',
                                                        'Boarding Master',
                                                        'Project Master',
                                                        'Chief Cooker',
                                                        'Security Guard'
                                                    ], 'default'=>'Subject Master'],
            'erAssigned'   => ['type' => 'DATE', 'null' => false],
            'erUnassigned' => ['type' => 'DATE', 'null' => true],
            'erCreated'    => ['type' => 'DATETIME', 'null' => true],
            'erUpdated'    => ['type' => 'DATETIME', 'null' => true],
            'erStatus'     => ['type' => 'ENUM', 'constraint' => [ 'Active', 
                                                                  'Promoted',
                                                                  'Removed',
                                                                  'Postponed',
                                                                  'Cancelled',
                                                                ], 'default'=>'Active'],
        ]);
        $this->forge->addKey('erID', true);
        $this->forge->createTable('employee_roles');

        // Tours
        $this->forge->addField([
            'touID'       => ['type' => 'INT', 'auto_increment' => true],
            'touName'     => ['type' => 'VARCHAR', 'constraint' => 150],
            'touCategory' => ['type' => 'ENUM("Departure", "Arrival", "Tour")', 'default'=>null],
            'touStart'    => ['type' => 'DATETIME', 'null' => true],
            'touEnd'      => ['type' => 'DATETIME', 'null' => true],
            'touStatus'   => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('touID', true);
        $this->forge->createTable('tours');

        // student_routestation
        $this->forge->addField([
            'srsID' => ['type' => 'INT', 'auto_increment' => true],
            'student_id' => ['type' => 'INT', 'null' => false],
            'route_station_id' => ['type' => 'INT', 'null' => false],
            'tour_id'          => ['type' => 'INT', 'null' => false],
            'assigned_by' => ['type' => 'INT', 'null' => true],
            'assigned_at' => ['type' => 'DATETIME', 'default' => null],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active'
            ],
        ]);
        $this->forge->addKey('srsID', true);
        $this->forge->addKey(['student_id', 'route_station_id', 'tour_id'], false, true, 'assignment_student_tour');
        $this->forge->addForeignKey('student_id', 'students', 'stuID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('route_station_id', 'route_stations', 'rsID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tour_id', 'tours', 'touID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assigned_by', 'employees', 'empID', 'SET NULL', 'CASCADE');
        $this->forge->createTable('student_routestation');

        // Tour_Route
        $this->forge->addField([
            'trID'        => ['type' => 'INT', 'auto_increment' => true],
            'tour_id'     => ['type' => 'INT'],
            'route_id'    => ['type' => 'INT'],
            'employee_id' => ['type' => 'INT'],
            'vehicle_id'  => ['type' => 'INT'],
            'trDeparture' => ['type' => 'DATETIME', 'null' => true],
            'trArrival'   => ['type' => 'DATETIME', 'null' => true],
            'trPosition'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'trStatus'    => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('trID', true);
        $this->forge->addForeignKey('tour_id', 'tours', 'touID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('route_id', 'routes', 'rouID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('employee_id', 'employees', 'empID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('vehicle_id', 'vehicles', 'veID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tour_routes');

        // Tour_Feedback
        $this->forge->addField([
            'tfID'        => ['type' => 'INT', 'auto_increment' => true],
            'student_id'  => ['type' => 'INT'],
            'gurdian_id'  => ['type' => 'INT'],
            'tfResponse'  => ['type' => 'TEXT', 'null' => true],
            'tfState'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'employee_id' => ['type' => 'INT'],
            'tfComment'   => ['type' => 'TEXT', 'null' => true],
            'tfStatus'    => ['type' => 'VARCHAR', 'constraint' => 50],
            'tfCreated'   => ['type' => 'DATETIME', 'null' => true],
            'tfUpdated'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('tfID', true);
        $this->forge->addForeignKey('student_id', 'students', 'stuID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('gurdian_id', 'guardians', 'guID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('employee_id', 'employees', 'empID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tour_feedbacks');

        //Subjects
        $this->forge->addField([
            'subID'         => ['type'=>'INT', 'auto_increment'=>true],
            'subName'       => ['type'=>'VARCHAR', 'constraint'=>50],
            'subCode'       => ['type'=>'VARCHAR', 'constraint'=>10, 'unique'=>true],
            'subShort'      => ['type'=>'VARCHAR', 'constraint'=>10],
            'subCategory'   => ['type'=>'ENUM', 'constraint'=>['Core', 'Option'], 'default'=>'Core'],
            'subLevel'      => ['type'=>'ENUM', 'constraint'=>['O-level', 'A-Level', 'Both'], 'default'=>'Both'],
            'subCurriculum' => ['type'=>'ENUM', 'constraint'=>['Old', 'New', 'Both'], 'default'=>'Both'],
            'subCreated'    => ['type'=>'DATETIME', 'null'=>true],
            'subUpdated'    => ['type'=>'DATETIME', 'null'=>true],
        ]);
        $this->forge->addKey('subID', true);
        $this->forge->createTable('subjects');

        //Subjects and Streams(Arms)
        $this->forge->addField([
            'scID'          => ['type'=>'INT', 'auto_increment'=>true],
            'stream_id'     => ['type'=>'INT'],
            'subject_id'    => ['type'=>'INT'],
            'isMandatory'   => ['type'=>'TINYINT', 'constraint'=>1, 'default'=>1],
            'hasPractical'  => ['type'=>'TINYINT', 'constraint'=>1, 'default'=>null],
            'isSubsidiary'  => ['type'=>'TINYINT', 'constraint'=>1, 'default'=>null],
            'subCreated'    => ['type'=>'DATETIME', 'null'=>true],
            'subUpdated'    => ['type'=>'DATETIME', 'null'=>true],
        ]);
        $this->forge->addKey('scID', true);
        $this->forge->addKey(['subject_id', 'stream_id'], false, true);
        $this->forge->addForeignKey('stream_id', 'streams', 'sid', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'subjects', 'subID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subject_streams');

         // Academic Year (Session eg.: 2024, 2025, 2024/2025, 2025/2026)
        $this->forge->addField([
            'ayID'        => ['type' => 'VARCHAR', 'constraint' => 10, 'unique' => true],
            'ayName'      => ['type' => 'VARCHAR', 'constraint' => 25],
            'ayLevel'     => ['type' => 'VARCHAR', 'constraint' => 10],
            'ayStart'     => ['type' => 'DATE' ],
            'ayEnd'       => ['type' => 'DATE' ],
            'ayCreated'   => ['type' => 'DATETIME' ],
            'ayUpdated'   => ['type' => 'DATETIME', 'null'=>true],
            'ayStatus'    => ['type' => 'ENUM', 'constraint' => ['Current', 'Previous', 'Next', 'Old'], 'default'=>'Next'],
        ]);
        $this->forge->addKey('ayID', true);
        $this->forge->createTable('academicYears');

         // Terms (1st, 2nd, I, II, etc)
        $this->forge->addField([
            'tID'        => ['type' => 'VARCHAR', 'constraint' => 10, 'unique' => true],
            'tName'      => ['type' => 'VARCHAR', 'constraint' => 25],
            'ay_id'      => ['type' => 'VARCHAR', 'constraint'=>10],
            'tStart'     => ['type' => 'DATE' ],
            'tEnd'       => ['type' => 'DATE' ],
            'tCreated'   => ['type' => 'DATETIME' ],
            'tUpdated'   => ['type' => 'DATETIME', 'null'=>true],
            'tCategory'  => ['type' => 'ENUM', 'constraint' => ['Even', 'Old'], 'default'=>'Old'],
            'tStatus'   => ['type' => 'ENUM', 'constraint' => ['Current', 'Previous', 'Next', 'Old'], 'default'=>'Next'],
        ]);
        $this->forge->addKey('tID', true);
        $this->forge->addForeignKey('ay_id', 'academicYears', 'ayID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('terms');

        //Examination
        $this->forge->addField([
            'exID'       => ['type' => 'VARCHAR', 'constraint'=>11, 'unique'=>true],
            'exName'     => ['type' => 'VARCHAR', 'constraint'=>75],
            'class_id'   => ['type' => 'INT'],
            'term_id'    => ['type' => 'VARCHAR', 'constraint' => 10],
            'exSubjects' => ['type' => 'TEXT'],
            'exCreated'  => ['type' => 'DATETIME' ],
            'exUpdated'  => ['type' => 'DATETIME', 'null'=>true],
            'exStatus'   => ['type' => 'ENUM', 'constraint' => ['progress', 'old', 'next', 'canceled'], 'default'=>'next']
        ]);
        $this->forge->addKey('exID', true);
        $this->forge->addForeignKey('class_id', 'classes', 'cid', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('term_id', 'terms', 'tID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('exams');

        // Raw Results
        $this->forge->addField([
            'raID'        => ['type' => 'INT', 'auto_increment' => true],
            'exam_id'     => ['type' => 'VARCHAR', 'constraint' => 11],
            'student_id'  => ['type' => 'INT'],
            'subject_id'  => ['type' => 'INT'],
            'raScore'     => ['type' => 'INT' ],
            'raGrade'     => ['type' => 'CHAR', 'constraint'=>3],
            'raCreated'   => ['type' => 'DATETIME' ],
            'raCreatedBy' => ['type' => 'INT'],
            'raUpdated'   => ['type' => 'DATETIME', 'null'=>true],
            'raStatus'    => ['type' => 'ENUM', 'constraint' => ['raw', 'deleted', 'compiled'], 'default'=>'raw'],
        ]);
        $this->forge->addKey('raID', true);
        $this->forge->addForeignKey('exam_id', 'exams', 'exID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'students', 'stuID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'subjects', 'subID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('raCreatedBy', 'employees', 'empID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('rawResults');

        // Compiled Results
        $this->forge->addField([
            'crID'        => ['type' => 'INT', 'auto_increment' => true],
            'exam_id'     => ['type' => 'VARCHAR', 'constraint' => 11],
            'student_id'  => ['type' => 'INT' ],
            'crResults'   => ['type' => 'TEXT' ], //Sture in JSON array
            'crCreated'   => ['type' => 'DATETIME' ],
            'crCompiledBy'=> ['type' => 'INT'],
            'raUpdated'   => ['type' => 'DATETIME', 'null'=>true],
            'raStatus'    => ['type' => 'ENUM', 'constraint' => ['sent', 'forwarded', 'compiled'], 'default'=>'compiled'],
        ]);
        $this->forge->addKey('crID', true);
        $this->forge->addForeignKey('exam_id', 'exams', 'exID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'students', 'stuID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('crCompiledBy', 'employees', 'empID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('compiledResults');

        //Subject master on specific classes
        $this->forge->addField([
            'code'        => ['type' => 'VARCHAR', 'constraint'=>15, 'unique'=>true],
            'employee_id' => ['type' =>'INT'],
            'stream_id'   => ['type' =>'INT'],
            'assigneDate' => ['type' => 'DATE'],
            'todate'      => ['type' => 'DATE', 'null'=>true, 'default'=>null],
            'created'     => ['type' => 'DATETIME', 'null'=>true],
            'status'      => ['type' => 'ENUM', 'constraint' => ['Active', 'Canceled', 'Old', 'Next'], 'default'=>'Active']
        ]);
        $this->forge->addKey('code', true);
        $this->forge->addKey(['employee_id', 'stream_id'], false, true, 'subject_master_info');
        $this->forge->addForeignKey('employee_id', 'employees', 'empID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('stream_id', 'subject_streams', 'scID', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employee_teaches');

        //Class master on specific classes
        $this->forge->addField([
            'cmID'        => ['type' => 'VARCHAR', 'constraint'=>15, 'unique'=>true],
            'employee_id' => ['type' =>'INT'],
            'stream_id'   => ['type' =>'INT'],
            'cmAssigneDate' => ['type' => 'DATE'],
            'cmTodate'      => ['type' => 'DATE', 'null'=>true, 'default'=>null],
            'cmCreated'     => ['type' => 'DATETIME', 'null'=>true],
            'cmStatus'      => ['type' => 'ENUM', 'constraint' => ['Active', 'Canceled', 'Old', 'Next'], 'default'=>'Active']
        ]);
        $this->forge->addKey('cmID', true);
        $this->forge->addKey(['employee_id', 'stream_id'], false, true, 'class_master_info');
        $this->forge->addForeignKey('employee_id', 'employees', 'empID', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('stream_id', 'streams', 'sid', 'CASCADE', 'CASCADE');
        $this->forge->createTable('class_masters');
    }//End of UP

    public function down(){
        $this->forge->dropTable('class_masters');
        $this->forge->dropTable('employee_teaches');
        $this->forge->dropTable('compiledResults');
        $this->forge->dropTable('rawResults');
        $this->forge->dropTable('exams');
        $this->forge->dropTable('terms');
        $this->forge->dropTable('academicYears');
        $this->forge->dropTable('subject_streams');
        $this->forge->dropTable('subjects');
        $this->forge->dropTable('streams');
        $this->forge->dropTable('classes');
        $this->forge->dropTable('tour_feedbacks');
        $this->forge->dropTable('tour_routes');        
        $this->forge->dropTable('student_routestation');
        $this->forge->dropTable('tours');
        $this->forge->dropTable('employee_roles');
        $this->forge->dropTable('employees');
        $this->forge->dropTable('students');
        $this->forge->dropTable('guardians');
        $this->forge->dropTable('vehicles');
        $this->forge->dropTable('route_stations');
        $this->forge->dropTable('stations');
        $this->forge->dropTable('routes');
        $this->forge->dropTable('streets');
        $this->forge->dropTable('wards');
        $this->forge->dropTable('districts');
        $this->forge->dropTable('regions');
    }
}