<?php namespace App\Services;

use App\Models\AcademicyearModel;
use App\Models\TermModel;
use App\Models\SchoolSettingModel;

class AcademicCalendarService
{
    protected $ay;
    protected $term;
    protected $school;

    public function __construct()
    {
        $this->ay     = new AcademicyearModel();
        $this->term   = new TermModel();
        $this->school = new SchoolSettingModel();
    }

    public function currentAY()
    {
        $settings = $this->school->find(1);
        return $settings['current_ay'] ?? null;
    }

    public function currentTerm()
    {
        $settings = $this->school->find(1);
        return $settings['current_term'] ?? null;
    }

    public function setCurrentAcademicYear($ayID)
    {
        $this->ay->where('ayID !=', $ayID)
                 ->set(['ayStatus' => 'Previous'])
                 ->update();

        $this->ay->update($ayID, ['ayStatus' => 'Current']);

        return $this->school->update(1, ['current_ay' => $ayID]);
    }

    public function setCurrentTerm($termID)
    {
        $this->term->where('id !=', $termID)
                   ->set(['status' => 'Old'])
                   ->update();

        $this->term->update($termID, ['status' => 'Current']);

        return $this->school->update(1, ['current_term' => $termID]);
    }
}
