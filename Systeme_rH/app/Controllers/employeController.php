<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\EmployeModel;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
class employeController extends BaseController
{
    public function pageInsert()
    {
        $typesCongeModel = new TypeCongeModel();
        $soldesModel = new SoldeModel();
        $typesConge = $typesCongeModel->getAllTypesConge();
        $soldes = $soldesModel->getSoldeByEmploye(session()->get('id'));
        $data['typesConge'] = $typesConge;
        $data['soldes'] = $soldes;

        return view('employes/index', $data);
    }
    public function insert()
    {
        $congeModel = new CongeModel();
        $data = [
            'id_employe' => session()->get('id'),
            'id_type_conge' => $this->request->getPost('type_conge'),
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin'),
            'nb_jours' => $this->request->getPost('nb_jours'),
            'motif' => $this->request->getPost('motif')
        ];

        $congeModel->insertConge($data);
        $message = "Demande de congé soumise avec succès.";
        session()->setFlashdata('success', $message);
        return redirect()->to('/employe/insert');
    }
    public function listDemandes()
    {
        $congeModel = new CongeModel();
        $demandes = $congeModel->getCongesByEmploye(session()->get('id'));
        $data['demandes'] = $demandes;
        return view('employes/list_demandes', $data);
    }
    public function cancel($id)
    {
        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);

        if ($conge && $conge['id_employe'] == session()->get('id') && $conge['statut'] == 'en_attente') {
            $congeModel->update($id, ['statut' => 'annule']);
            session()->setFlashdata('success', 'Demande de congé annulée avec succès.');
        } else {
            session()->setFlashdata('error', 'Impossible d\'annuler cette demande de congé.');
        }

        return redirect()->to('/employe/listDemandes');
    }
    public function dashboard()
    {
        $modelSolde = new SoldeModel();
        $modelConge = new CongeModel();
        $soldes = $modelSolde->getSoldeByEmploye(session()->get('id'));
        $demandes = $modelConge->getCongesByEmploye(session()->get('id'));

        $data['soldes'] = $soldes;
        $data['demandes'] = $demandes;
        return view('employes/dashboard', $data);
    }
    public function profile()
    {
        $employeModel = new EmployeModel();
        $employe = $employeModel->find(session()->get('id'));
        $data['employe'] = $employe;
        return view('employes/profile', $data);
    }     
}