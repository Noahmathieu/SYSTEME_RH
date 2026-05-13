<?php
namespace App\Models;
use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table = 'departements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'description'];
    protected $useTimestamps = false;
    public function getAllDepartements()
    {
        return $this->findAll();
    }
    public function insertDepartement($data)
    {
        return $this->insert($data);
    }
}