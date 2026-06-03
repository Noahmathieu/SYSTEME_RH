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
    public function getNombreCongesByType($id_employe): array
    {
        return $this->db->table('conges c')
            ->select('COUNT(C.id_type_conge) nb,TC.libelle libelle')
            ->where('C.id_employe=' . $id_employe)
            ->join('types_conges TC', 'C.id_type_conge=TC.id')
            ->groupBy('C.id_type_conge')
            ->get()
            ->getResultArray();
        // SELECT COUNT(C.id_type_conge),TC.libelle FROM conges AS C JOIN types_conges AS TC ON C.id_type_conge=TC.id WHERE C.id_employe=3 GROUP BY C.id_type_conge
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
    public function getCongesParMois($annee)
    {
        return $this->db->table('conges c')
            ->select("strftime('%m', c.date_debut) mois,COUNT(c.id) as nb")
            ->join('types_conges tc', 'c.id_type_conge = tc.id')
            ->where("strftime('%Y', c.date_debut)", $annee)
            ->where("c.statut", "approuve")
            ->groupBy("mois")
            ->orderBy("mois", "ASC")
            ->get()
            ->getResultArray();
        //SELECT strftime('%m', date_debut) AS mois,COUNT(id) AS nb FROM conges WHERE strftime('%Y', date_debut) = '2026' AND statut="approuve" GROUP BY mois ORDER BY mois ASC;

    }
    public function getCongesJour($annee)
    {
        return $this->db->table('conges c')
            ->select("strftime('%m', c.date_debut) mois,SUM(nb_jours) as nb")
            ->join('types_conges tc', 'c.id_type_conge = tc.id')
            ->where("strftime('%Y', c.date_debut)", $annee)
            ->where("c.statut", "approuve")
            ->groupBy("mois")
            ->orderBy("mois", "ASC")
            ->get()
            ->getResultArray();
        //SELECT strftime('%m', date_debut) AS mois,SUM(nb_jours) as jour FROM conges WHERE strftime('%Y', date_debut) = '2026' AND statut="approuve" GROUP BY mois ORDER BY mois ASC;

    }
}
