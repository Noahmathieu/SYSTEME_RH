<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function form()
    {
        $model = new UserModel();
        $user = $model->findAll();
        return view('auth/login', [
            'users' => $user,
        ]);
    }

    private function passwordMatches(string $plainPassword, string $storedPassword): bool
    {
        return hash_equals($storedPassword, $plainPassword);
    }

    public function login()
    {
        $model = new UserModel();
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        $user = $model->findByEmailWithRole($email);

        if (!$user || !(bool) $user['actif'] || $password === '' || !$this->passwordMatches($password, (string) $user['password'])) {
            return view('auth/login', [
                'erreur' => 'Email ou mot de passe incorrect',
            ]);
        }

        session()->set('user', [
            'id' => $user['id'],
            'prenom' => $user['prenom'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role_id' => $user['id_role'],
            'role' => $user['role'],
        ]);

        return match ($user['role']) {
            'admin' => redirect()->to(site_url('admin/dashboard')),
            'rh' => redirect()->to(site_url('rh/dashboard')),
            'employe' => redirect()->to(site_url('employe/dashboard')),
            default => redirect()->to(site_url('login'))->with('erreur', 'Rôle utilisateur non pris en charge'),
        };
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
