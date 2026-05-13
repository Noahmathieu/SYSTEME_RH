<?php
namespace App\Models;
use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_employe', 'id_type_conge', 'date_debut', 'date_fin', 'nb_jours', 'motif', 'statut', 'commentaire_rh'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getCongesByEmploye($id_employe)
    {
        return $this->where('id_employe', $id_employe)->findAll();
    }
    public function insertConge($data)
    {
        return $this->insert($data);
    }
    
}