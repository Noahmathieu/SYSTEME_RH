<?php
namespace App\Models;
use CodeIgniter\Model;

class TypeConge extends Model
{
    protected $table = 'types_conges';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'jours_annuels', 'deductible'];
    protected $useTimestamps = false;

    public function getAllTypesConge()
    {
        return $this->findAll();
    }
}