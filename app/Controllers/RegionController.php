<?php

namespace App\Controllers;
use App\Models\RegionModel;
use CodeIgniter\Controller;

class RegionController extends Controller
{
    protected $regionModel;

    public function __construct()
    {
        $this->regionModel = new RegionModel();
    }

    public function index()
    {
        $data['regions'] = $this->regionModel->findAll();
        return view('region/index', $data);
    }

    public function create()
    {
        return view('region/create');
    }

    public function store()
    {
        $this->regionModel->save([
            'regName' => $this->request->getPost('regName'),
            'regZone' => $this->request->getPost('regZone'),
            'regLand' => $this->request->getPost('regLand'),
        ]);
        return redirect()->to('/region');
    }

    public function edit($id)
    {
        $data['region'] = $this->regionModel->find($id);
        return view('region/edit', $data);
    }

    public function update($id)
    {
        $this->regionModel->update($id, [
            'regName' => $this->request->getPost('regName'),
            'regZone' => $this->request->getPost('regZone'),
            'regLand' => $this->request->getPost('regLand'),
        ]);
        return redirect()->to('/region');
    }

    public function delete($id)
    {
        $this->regionModel->delete($id);
        return redirect()->to('/region');
    }
}