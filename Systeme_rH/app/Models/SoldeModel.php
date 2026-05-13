<?php
namespace App\Models;
use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table = 'soldes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_employe', 'id_type_conge', 'annee', 'jours_attribues', 'jours_pris', 'pris'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getSoldeByEmploye($id_employe)
    {
        return $this->where('id_employe', $id_employe)->findAll();
    }
}