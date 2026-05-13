<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\RoleModel;

class AuthController extends BaseController
{
    public function login(): string
    {
        return view('auth/login');
    }

    public function attempt()
    {
        $email = (string) $this->request->getPost('email');
        $employeModel = new EmployeModel();
        $roleModel = new RoleModel();
        $employe = null;
        $roleName = 'employe';

        if ($email !== '') {
            $employe = $employeModel->where('email', $email)->first();
        }

        if (!$employe) {
            $employe = $employeModel->orderBy('id', 'asc')->first();
        }

        if ($employe) {
            if (!empty($employe['id_role'])) {
                $roleRow = $roleModel->find($employe['id_role']);
                if ($roleRow && !empty($roleRow['nom'])) {
                    $roleName = $roleRow['nom'];
                }
            }
            session()->set([
                'id' => $employe['id'],
                'role' => $roleName,
                'role_id' => $employe['id_role'] ?? null,
            ]);
        }

        $role = $roleName;
        $destination = '/employe/dashboard';
        if ($role === 'admin') {
            $destination = '/admin/dashboard';
        } elseif ($role === 'rh') {
            $destination = '/rh/demandes';
        }

        return redirect()->to($destination);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
