<?php

namespace App\Controllers;
use App\Models\VehicleModel;
use App\Models\RegionModel;
use CodeIgniter\Controller;

class Vehicles extends Controller{
    protected $vehicleModel;
    protected $regionModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
        $this->regionModel = new RegionModel();
    }

    public function index()
    {
        $data['vehicles'] = $this->vehicleModel->findAll();
        return view('tour/vehicles', $data);
    }

    public function create() {
        $data = [
            'pageTitle' => 'Add Vehicles',
            ];
        return view('tour/add_vehicles', $data);
    }

    public function store()
    {
        $this->vehicleModel->save([
            'vePlateNumber' => $this->request->getPost('vePlateNumber'),
            'veModel' => $this->request->getPost('veModel'),
            'veOwnership' => $this->request->getPost('veOwnership'),
            'veNamed' => $this->request->getPost('veNamed'),
        ]);
        $vhc = $this->request->getPost('vePlateNumber').' '.$this->request->getPost('veModel');
        return redirect()->to('/trans/buses')->with('success', '');
    }

    public function edit($id)
    {
        $data['vehicle'] = $this->vehicleModel->find($id);
        return view('vehicle/edit', $data);
    }

    public function update($id)
    {
        $this->vehicleModel->update($id, [
            'vePlateNumber' => $this->request->getPost('vePlateNumber'),
            'veModel' => $this->request->getPost('veModel'),
            'veOwnership' => $this->request->getPost('veOwnership'),
            'veNamed' => $this->request->getPost('veNamed'),
        ]);
        return redirect()->to('/vehicle');
    }

    public function delete($id)
    {
        $this->vehicleModel->delete($id);
        return redirect()->to('/vehicle');
    }
}
