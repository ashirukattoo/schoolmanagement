<?php namespace App\Controllers;

use App\Services\SchoolContextService;
use App\Models\SchoolSettingModel;

class SchoolSettings extends BaseController
{
    protected $service;
    protected $model;

    public function __construct()
    {
        $this->service = new SchoolContextService();
        $this->model   = new SchoolSettingModel();
    }

    public function index()
    {
        return view('admin/settings/school', [
            'school' => $this->service->school(),
            'ay'     => $this->service->currentAcademicYear(),
            'term'   => $this->service->currentTerm(),
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        $this->model->update($id, [
            'school_name'  => $this->request->getPost('school_name'),
            'school_motto' => $this->request->getPost('school_motto'),
            'school_email' => $this->request->getPost('school_email'),
            'school_phone' => $this->request->getPost('school_phone'),
            'school_address' => $this->request->getPost('school_address'),
            'current_ay'   => $this->request->getPost('current_ay'),
            'current_term' => $this->request->getPost('current_term'),
        ]);

        return redirect()->back()->with('success', 'School settings updated');
    }
}
