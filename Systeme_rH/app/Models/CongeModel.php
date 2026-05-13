<?php
namespace App\Models;
use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_employe',
        'id_type_conge',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'statut',
        'commentaire_rh',
        'traite_par',
    ];
    protected $useTimestamps = false;

    public function getCongesByEmploye($id_employe)
    {
        return $this->where('id_employe', $id_employe)
            ->orderBy('id', 'desc')
            ->findAll();
    }
    public function insertConge($data)
    {
        return $this->insert($data);
    }
    public function getCongesByEmployeWithType(int $id_employe): array
    {
        return $this->db->table('conges c')
            ->select('c.*, t.libelle as type_conge')
            ->join('types_conges t', 't.id = c.id_type_conge', 'left')
            ->where('c.id_employe', $id_employe)
            ->orderBy('c.id', 'desc')
            ->get()
            ->getResultArray();
    }

    public function getCongesWithSoldes(array $statuts = [], int $limit = 0): array
    {
        $builder = $this->db->table('conges c')
            ->select('c.*, e.nom, e.prenom, e.email')
            ->select('t.libelle as type_conge')
            ->select('s.jours_attribues, s.jours_pris, s.annee')
            ->select('(s.jours_attribues - s.jours_pris) as solde_restant')
            ->join('employes e', 'e.id = c.id_employe', 'left')
            ->join('types_conges t', 't.id = c.id_type_conge', 'left')
            ->join('soldes s', 's.id_employe = c.id_employe AND s.id_type_conge = c.id_type_conge', 'left')
            ->orderBy('c.id', 'desc');

        if (!empty($statuts)) {
            $builder->whereIn('c.statut', $statuts);
        }

        if ($limit > 0) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

}