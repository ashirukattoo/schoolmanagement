<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Employee extends Seeder
{
    public function run(){
        $this->db->table('employees')->insertBatch([
            ['empFname' => 'Ashiru', 
            'empMname' => 'Esmail', 
            'empSurname' => 'Hussein', 
            'empSex' => 'Male', 
            'empDob'=>'1996-09-20',
            'empEmail' => 'ashirukattoo@gmail.com', 
            'empPassword' => password_hash('nikwo', PASSWORD_DEFAULT), 
            'empHired' => '2025-12-13',
            'empRegisterd' => date('Y-m-d H:m:s'),
            'empStatus' => 'Active'],
            ['empFname' => 'Jovin', 
            'empMname' => 'Joseph', 
            'empSurname' => 'Kagaruki', 
            'empSex' => 'Male', 
            'empDob'=>'1999-10-22',
            'empEmail' => 'jovinkagaruki@gmail.com', 
            'empPassword' => password_hash('joseph1234', PASSWORD_DEFAULT), 
            'empHired' => '2025-04-11',
            'empRegisterd' => date('Y-m-d H:m:s'),
            'empStatus' => 'Active'],
        ]);
    }
}
