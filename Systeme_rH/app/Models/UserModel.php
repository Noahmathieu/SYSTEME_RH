<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nom', 'prenom', 'password', 'email', 'id_role', 'id_departement', 'date_embauche', 'actif'];

    public function findByEmailWithRole(string $email): ?array
    {
        return $this->select('employes.id, employes.nom, employes.prenom, employes.email, employes.password, employes.id_role, employes.actif, roles.nom as role')
            ->join('roles', 'roles.id = employes.id_role', 'left')
            ->where('employes.email', $email)
            ->first();
    }
}