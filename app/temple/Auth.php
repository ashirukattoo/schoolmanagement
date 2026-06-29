<?php

namespace App\Controllers;

use App\Models\PersonModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        helper(['form']);
        $session = session();
        $model   = new PersonModel();

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'fname'     => 'required|min_length[2]',
                'surname'   => 'required|min_length[2]',
                'username'  => 'required|valid_email|is_unique[users.username]',
                'password'  => 'required|min_length[6]',
                'cpassword' => 'matches[password]',
            ];

            if (!$this->validate($rules)) {
                return view('auth/register', [
                    'validation' => $this->validator
                ]);
            }

            $model->insert([
                'fname'    => $this->request->getPost('fname'),
                'mname'    => $this->request->getPost('mname'),
                'surname'  => $this->request->getPost('surname'),
                'dob'      => $this->request->getPost('dob'),
                'sex'      => $this->request->getPost('sex'),
                'username' => $this->request->getPost('username'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'status'   => 'active',
                'created'  => date('Y-m-d H:i:s'),
                'updated'  => date('Y-m-d H:i:s')
            ]);

            $session->setFlashdata('success', 'Registration successful! You can now login.');
            return redirect()->to('/auth/login');
        }

        return view('auth/register');
    }

    public function login()
    {
        helper(['form']);
        $session = session();
        $model   = new PersonModel();

        if ($this->request->getMethod() === 'post') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = $model->where('username', $username)->first();

            if ($user && password_verify($password, $user['password'])) {
                $session->set([
                    'uid'        => $user['uid'],
                    'username'   => $user['username'],
                    'isLoggedIn' => true
                ]);

                return redirect()->to('/dashboard');
            }

            $session->setFlashdata('error', 'Invalid email or password');
            return redirect()->back();
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}
