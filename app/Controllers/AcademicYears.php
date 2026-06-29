<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AcademicyearModel;
use App\Models\TermModel;
use App\Services\AcademicCalendarService;

class AcademicYears extends BaseController
{
    protected $model;
    protected $service;
    protected $term;
    protected $ay;

    public function __construct()
    {
        $this->term   = new TermModel();
        $this->ay      = new AcademicyearModel();
        $this->model   = new AcademicyearModel();
        $this->service = new AcademicCalendarService();
    }

    public function index()
    {
        return view('admin/liabilities/years', [
            'pageTitle' =>'Academic Years',
            'years' => $this->model->getAll()
        ]);
    }

    public function create()
    {
        $this->model->insert($this->request->getPost());
        return redirect()->back()->with('success', 'Academic year added');
    }

    public function setCurrent($id)
    {
        $this->service->setCurrentAcademicYear($id);
        return redirect()->back()->with('success', 'Current academic year updated');
    }


    public function indexx(){
        $ayID = service('academicCalendar')->currentAY();
        return view('admin/liabilities/settings', [
            'pageTitle' => 'Academic Years\' Terms',
            'terms' => $this->term->where('ay_id', $ayID)->findAll(),
            'ayID'  => $ayID
        ]);
    }
    public function terms($ayID){
        return view('admin/liabilities/settings', [
            'pageTitle' => 'Academic Year Terms',
            'ayID'      => $ayID,
            'terms'     => $this->term->where('ay_id', $ayID)->findAll(),
        ]);
    }
    public function createe(){
        $data =[
            'tID' => $this->term->getTermId(),
            'tName' =>$this->request->getPost('name'),
            'ay_id' =>$this->request->getPost('year'),
            'tStart' =>$this->request->getPost('start'),
            'tEnd'  =>$this->request->getPost('end'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null,
            'tStatus'  => 'Next',
            'deleted_at' => null
        ];
        $this->term->saveTerm($data);
        return redirect()->back()->with('success', 'Term added');
    }

    public function setCurrentt($id)
    {
        $this->service->setCurrentTerm($id);
        return redirect()->back()->with('success', 'Current term updated');
    }
}

