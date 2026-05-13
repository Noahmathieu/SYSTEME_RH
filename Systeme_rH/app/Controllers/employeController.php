<?php
namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
class employeController extends BaseController
{
    public function dashboard()
    {
        $idEmploye = $this->resolveEmployeId();
        if (!$idEmploye) {
            return redirect()->to('/login');
        }

        $modelSolde = new SoldeModel();
        $modelConge = new CongeModel();

        $soldes = $modelSolde->getSoldeByEmployeWithType($idEmploye);
        $demandes = $modelConge->getCongesByEmployeWithType($idEmploye);

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

        $totalAttribues = 0;
        $totalRestant = 0;
        foreach ($soldes as $solde) {
            $attribues = (int) $solde['jours_attribues'];
            $pris = (int) $solde['jours_pris'];
            $totalAttribues += $attribues;
            $totalRestant += max(0, $attribues - $pris);
        }

        $recentDemandes = array_slice($demandes, 0, 5);

        return view('employe/dashboard', [
            'soldes' => $soldes,
            'demandes' => $recentDemandes,
            'stats' => $stats,
            'totalAttribues' => $totalAttribues,
            'totalRestant' => $totalRestant,
        ]);
    }

    public function create()
    {
        $idEmploye = $this->resolveEmployeId();
        if (!$idEmploye) {
            return redirect()->to('/login');
        }

        $typesCongeModel = new TypeCongeModel();
        $soldesModel = new SoldeModel();

        $typesConge = $typesCongeModel->findAll();
        $soldes = $soldesModel->getSoldeByEmployeWithType($idEmploye);

        $soldesByType = [];
        foreach ($soldes as $solde) {
            $soldesByType[$solde['id_type_conge']] = $solde;
        }

        return view('employe/create', [
            'typesConge' => $typesConge,
            'soldes' => $soldes,
            'soldesByType' => $soldesByType,
        ]);
    }

    public function store()
    {
        $idEmploye = $this->resolveEmployeId();
        if (!$idEmploye) {
            return redirect()->to('/login');
        }

        $typeConge = (int) $this->request->getPost('type_conge');
        $dateDebut = (string) $this->request->getPost('date_debut');
        $dateFin = (string) $this->request->getPost('date_fin');
        $motif = (string) $this->request->getPost('motif');

        if ($typeConge <= 0 || $dateDebut === '' || $dateFin === '') {
            session()->setFlashdata('error', 'Veuillez renseigner tous les champs obligatoires.');
            return redirect()->back()->withInput();
        }

        try {
            $start = new \DateTime($dateDebut);
            $end = new \DateTime($dateFin);
        } catch (\Exception $exception) {
            session()->setFlashdata('error', 'Dates invalides.');
            return redirect()->back()->withInput();
        }

        if ($end < $start) {
            session()->setFlashdata('error', 'La date de fin doit etre apres la date de debut.');
            return redirect()->back()->withInput();
        }

        $nbJours = $start->diff($end)->days + 1;
        $annee = (int) $start->format('Y');

        $soldeModel = new SoldeModel();
        $typeModel = new TypeCongeModel();
        $type = $typeModel->find($typeConge);
        $deductible = $type ? (int) $type['deductible'] : 1;

        $solde = $soldeModel->getSoldeForEmployeTypeYear($idEmploye, $typeConge, $annee);

        if ($deductible === 1) {
            if (!$solde) {
                session()->setFlashdata('error', 'Aucun solde n\'a ete trouve pour ce type de conge.');
                return redirect()->back()->withInput();
            }

            $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            if ($restant < $nbJours) {
                session()->setFlashdata('error', 'Solde insuffisant pour cette demande.');
                return redirect()->back()->withInput();
            }
        }

        $congeModel = new CongeModel();
        $congeModel->insert([
            'id_employe' => $idEmploye,
            'id_type_conge' => $typeConge,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_jours' => $nbJours,
            'motif' => $motif,
            'statut' => 'en_attente',
        ]);

        session()->setFlashdata('success', 'Demande de conge soumise avec succes.');
        return redirect()->to('/employe/dashboard');
    }

    public function conges()
    {
        $idEmploye = $this->resolveEmployeId();
        if (!$idEmploye) {
            return redirect()->to('/login');
        }

        $congeModel = new CongeModel();
        $demandes = $congeModel->getCongesByEmployeWithType($idEmploye);

        return view('employe/index', ['demandes' => $demandes]);
    }

    public function cancel($id)
    {
        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);

        $idEmploye = $this->resolveEmployeId();
        if ($conge && $idEmploye && $conge['id_employe'] == $idEmploye && $conge['statut'] == 'en_attente') {
            $congeModel->update($id, ['statut' => 'annule']);
            session()->setFlashdata('success', 'Demande de congé annulée avec succès.');
        } else {
            session()->setFlashdata('error', 'Impossible d\'annuler cette demande de congé.');
        }

        return redirect()->to('/employe/conges');
    }
    public function profile()
    {
        return redirect()->to('/employe/dashboard');
    }     
}