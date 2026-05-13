<?php
namespace App\Models;
use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table = 'soldes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_employe', 'id_type_conge', 'annee', 'jours_attribues', 'jours_pris'];
    protected $useTimestamps = false;

    public function getSoldeByEmploye($id_employe)
    {
        return $this->where('id_employe', $id_employe)->findAll();
    }

    public function getSoldeByEmployeWithType(int $id_employe): array
    {
        return $this->db->table('soldes s')
            ->select('s.*, t.libelle, t.jours_annuels, t.deductible')
            ->join('types_conges t', 't.id = s.id_type_conge', 'left')
            ->where('s.id_employe', $id_employe)
            ->orderBy('t.libelle', 'asc')
            ->get()
            ->getResultArray();
    }

    public function getSoldeForEmployeTypeYear(int $id_employe, int $id_type_conge, int $annee): ?array
    {
        return $this->where('id_employe', $id_employe)
            ->where('id_type_conge', $id_type_conge)
            ->where('annee', $annee)
            ->first();
    }
}