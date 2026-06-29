<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setup extends Migration
{
    public function up(){
        $this->forge->addField([
            'id'           =>['type' => 'INT', 'auto_increment'=> true],
            'school_name'  =>['type' => 'VARCHAR', 'constraint'=>255, 'default'=>'Lyamahoro'],
            'school_logo'  =>['type' => 'VARCHAR', 'constraint'=>255, 'default'=>null],
            'ministry'     =>['type' => 'VARCHAR', 'constraint'=>255, 'default'=>null],
            'shool_adress' =>['type' => 'VARCHAR', 'constraint'=>255, 'default'=>null],
            'school_street'=>['type' => 'INT', 'default'=>null],
            'school_email' =>['type' => 'VARCHAR', 'constraint'=>120],
            'school_phone' =>['type' => 'VARCHAR', 'constraint'=>20],
            'school_status'=>['type' => 'ENUM("Active", "Inactive", "Pending")', 'default'=>'Active']
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings');
    }

    public function down()    {
        $this->forge->dropTable('settings');
    }
}
