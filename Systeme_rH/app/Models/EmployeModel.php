<?php
namespace App\Models;
use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'prenom', 'email', 'password', 'id_role', 'id_departement', 'date_embauche', 'actif'];
    protected $useTimestamps = false;


}