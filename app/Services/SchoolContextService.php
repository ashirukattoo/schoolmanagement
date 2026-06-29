<?php namespace App\Services;

use App\Models\SchoolSettingModel;
use App\Models\AcademicYearModel;
use App\Models\TermModel;

class SchoolContextService
{
    protected $settings;
    protected $ayModel;
    protected $termModel;

    public function __construct()
    {
        $this->settings  = new SchoolSettingModel();
        $this->ayModel   = new AcademicYearModel();
        $this->termModel = new TermModel();
    }

    public function school()
    {
        return $this->settings->first();
    }

    public function currentAcademicYear()
    {
        $school = $this->school();
        return $this->ayModel->find($school['current_ay']);
    }

    public function currentTerm()
    {
        $school = $this->school();
        return $this->termModel->find($school['current_term']);
    }
}
