<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $user = $session->get('user');
        $allowedRoles = [];

        foreach ($arguments ?? [] as $argument) {
            foreach (explode(',', (string) $argument) as $role) {
                $role = trim($role);

                if ($role !== '') {
                    $allowedRoles[] = $role;
                }
            }
        }

        $currentRole = is_array($user) ? ($user['role'] ?? $user['role_id'] ?? null) : null;

        if (!$user || !$currentRole || !in_array((string) $currentRole, $allowedRoles, true)) {
            return redirect()->to('/login')->with('erreur', 'Accès refusé : droits insuffisants');
        }
    }
    public function after(RequestInterface $request, ResponseInterface
    $response, $arguments = null)
    {
        // Rien à faire après
    }
}
