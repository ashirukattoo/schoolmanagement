<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmployeeAlter extends Migration
{
    public function up() {
        $this->forge->dropColumn('employees', 'empRole');
        $fields = [
            'rolID'       => ['type'=>'INT', 'unique'=>true],
            'rolName'     => ['type'=>'VARCHAR', 'constraint'=>45, 'unique'=>true],
            'rolCategory' => ['type'=>'ENUM', 'constraint'=>['Admin', 'Teacher', 'Staff', 'Academic'], 'default'=>'Staff'],
            'rolCreated'  => ['type'=>'DATETIME', 'null'=>false]
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('rolID', true);
        $this->forge->createTable('roles');

        $fields = [
            'emrID'        => ['type'=>'INT', 'unique'=>true],
            'employee_id'  => ['type'=>'INT' ],
            'role_id'      => ['type'=>'INT' ],
            'emrCreated'   => ['type'=>'DATETIME', 'null'=>false]
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('emrID', true);
        $this->forge->addForeignKey('employee_id', 'employees', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('role_id', 'roles', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employee_roles');
    }

    public function down()  {
        $this->forge->dropTable('employees');
        $this->forge->dropTable('roles');
        $this->forge->addColumn('employees', ['empRole' => ['type' => 'ENUM', 'constraint' => ['Teacher', 'Staff', 'Technician', 'Specialist'], 'default'=>'Teacher'] ]);
    }
}
