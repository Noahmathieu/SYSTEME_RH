<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');
        if (!$role) {
            return redirect()->to('/login');
        }

        $allowed = is_array($arguments) ? $arguments : [];
        if (!empty($allowed) && !in_array($role, $allowed, true)) {
            return redirect()->to($this->roleHome($role));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }

    private function roleHome(string $role): string
    {
        if ($role === 'admin') {
            return '/admin/dashboard';
        }
        if ($role === 'rh') {
            return '/rh/demandes';
        }

        return '/employe/dashboard';
    }
}
