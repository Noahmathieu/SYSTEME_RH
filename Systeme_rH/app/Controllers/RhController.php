<?php
namespace App\Controllers;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\EmployeModel;
use App\Models\TypeCongeModel;
use App\Models\DepartementModel;

class RhController extends BaseController
{
    protected $congeModel;
    protected $soldeModel;
    protected $employeModel;
    protected $typeCongeModel;
    protected $departementModel;

    public function __construct()
    {
        $this->congeModel = new CongeModel();
        $this->soldeModel = new SoldeModel();
        $this->employeModel = new EmployeModel();
        $this->typeCongeModel = new TypeCongeModel();
        $this->departementModel = new DepartementModel();
    }

    public function index()
    {
        $departements = $this->departementModel->findAll();
        $demandes = $this->congeModel->getCongesWithSoldes();

        $stats = [
            'en_attente' => 0,
            'approuve' => 0,
            'refuse' => 0,
            'annule' => 0,
        ];

        foreach ($demandes as $demande) {
            $statut = $demande['statut'] ?? 'en_attente';
            if (isset($stats[$statut])) {
                $stats[$statut]++;
            }
        }

        return view('rh/index', [
            'departements' => $departements,
            'demandes' => $demandes,
            'stats' => $stats,
        ]);
    }
    public function dashboard()
    {
        return redirect()->to('/rh/demandes');
    }
    public function updateStatus($id)
    {
        $status = (string) $this->request->getPost('status');
        $commentaire = (string) $this->request->getPost('commentaire_rh');

        $conge = $this->congeModel->find($id);
        if ($conge) {
            if (!in_array($status, ['approuve', 'refuse', 'annule'], true)) {
                session()->setFlashdata('error', 'Statut invalide.');
                return redirect()->to('/rh/demandes');
            }

            if ($status === 'approuve') {
                $dateDebut = (string) ($conge['date_debut'] ?? '');
                $annee = (int) date('Y', strtotime($dateDebut));
                $type = $this->typeCongeModel->find($conge['id_type_conge']);
                $deductible = $type ? (int) $type['deductible'] : 1;
                $solde = $this->soldeModel->getSoldeForEmployeTypeYear(
                    (int) $conge['id_employe'],
                    (int) $conge['id_type_conge'],
                    $annee
                );

                if ($deductible === 1) {
                    if (!$solde) {
                        session()->setFlashdata('error', 'Solde introuvable pour cette demande.');
                        return redirect()->to('/rh/demandes');
                    }

                    $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
                    if ($restant < (int) $conge['nb_jours']) {
                        session()->setFlashdata('error', 'Solde insuffisant pour approuver cette demande.');
                        return redirect()->to('/rh/demandes');
                    }

                    $joursPris = (int) $solde['jours_pris'] + (int) $conge['nb_jours'];
                    $this->soldeModel->update($solde['id'], ['jours_pris' => $joursPris]);
                }
            }

            $rhId = $this->resolveEmployeId();
            $this->congeModel->update($id, [
                'statut' => $status,
                'commentaire_rh' => $commentaire,
                'traite_par' => $rhId,
            ]);

            session()->setFlashdata('success', 'Statut de la demande de conge mis a jour avec succes.');
        } else {
            session()->setFlashdata('error', 'Demande de congé non trouvée.');
        }
        return redirect()->to('/rh/demandes');
    }

    public function historique()
    {
        return $this->index();
    }

    public function soldes()
    {
        return $this->index();
    }
}