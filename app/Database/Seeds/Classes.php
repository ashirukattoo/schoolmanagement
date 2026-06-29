<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Classes extends Seeder{
    public function run($value=''){
        $this->db->table('classes')->insertBatch([
            ['named' => 'Form One', 
            'short' => 'FI', 
            'numeral' => '1', 
            'created'=>date('Y-m-d H:i:s')],
            ['named' => 'Form Two', 
            'short' => 'FII', 
            'numeral' => '2', 
            'created'=>date('Y-m-d H:i:s')],
            ['named' => 'Form Three', 
            'short' => 'FIII', 
            'numeral' => '3', 
            'created'=>date('Y-m-d H:i:s')],
            ['named' => 'Form Four', 
            'short' => 'FIV', 
            'numeral' => '4', 
            'created'=>date('Y-m-d H:i:s')],
        ]);
    }
    
}
