<?php
namespace App\Models;
use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table = 'types_conges';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'jours_annuels', 'deductible'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllTypesConge()
    {
        return $this->findAll();
    }
}