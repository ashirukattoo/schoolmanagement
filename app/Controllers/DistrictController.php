<?php

namespace App\Controllers;
use App\Models\DistrictModel;
use App\Models\RegionModel;
use CodeIgniter\Controller;

class DistrictController extends Controller
{
    protected $districtModel;
    protected $regionModel;

    public function __construct()
    {
        $this->districtModel = new DistrictModel();
        $this->regionModel = new RegionModel();
    }

    public function index()
    {
        $data['districts'] = $this->districtModel
            ->select('district.*, region.regName')
            ->join('region', 'region.regID = district.region_id')
            ->findAll();

        return view('district/index', $data);
    }

    public function create()
    {
        $data['regions'] = $this->regionModel->findAll();
        return view('district/create', $data);
    }

    public function store()
    {
        $this->districtModel->save([
            'disName' => $this->request->getPost('disName'),
            'region_id' => $this->request->getPost('region_id'),
        ]);
        return redirect()->to('/district');
    }

    public function edit($id)
    {
        $data['district'] = $this->districtModel->find($id);
        $data['regions'] = $this->regionModel->findAll();
        return view('district/edit', $data);
    }

    public function update($id)
    {
        $this->districtModel->update($id, [
            'disName' => $this->request->getPost('disName'),
            'region_id' => $this->request->getPost('region_id'),
        ]);
        return redirect()->to('/district');
    }

    public function delete($id)
    {
        $this->districtModel->delete($id);
        return redirect()->to('/district');
    }
}