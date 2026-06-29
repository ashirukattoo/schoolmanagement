<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Streams extends Seeder{
    public function run($value=''){
        $this->db->table('streams')->insertBatch([
            ['sName' => 'A', 
            'class_id' => 1, 
            'sCreated'=>date('Y-m-d H:i:s'),
            'sStatus' =>'Active'],
            ['sName' => 'B', 
            'class_id' => 1, 
            'sCreated'=>date('Y-m-d H:i:s'),
            'sStatus' =>'Active'],
            ['sName' => 'C', 
            'class_id' => 1, 
            'sCreated'=>date('Y-m-d H:i:s'),
            'sStatus' =>'Active'],
        ]);
    }
    
}
