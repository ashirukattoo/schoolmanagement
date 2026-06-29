<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThirdBatch extends Migration
{
    public function up(){
        $fields =[
            'crTotal'    => ['type'=>'INT', 'null', true],
            'crAverage'  => ['type'=>'DECIMAL', 'constraint'=>'6,2', 'null'=>true],
            'crPoints'   => ['type'=>'INT', 'null', true],
            'crDivision' => ['type'=>'VARCHAR', 'constraint'=>5, 'null', true],
            'crPosition' => ['type'=>'INT', 'null', true]       
        ];
        $this->forge->addColumn('compiledresults', $fields);
        
    }

    public function down(){
        $this->forge->dropColumn('compiledresults', ['crTotal', 'crAverage', 'crPoints', 'crDivision', 'crPosition']);
    }
}
