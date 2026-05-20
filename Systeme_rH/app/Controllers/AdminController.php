<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use App\Models\RoleModel;

class AdminController extends BaseController
{
    protected $employeModel;
    protected $congeModel;
    protected $departementModel;
    protected $soldeModel;
    protected $typeCongeModel;
    protected $roleModel;

    public function __construct()
    {
        $this->employeModel = new EmployeModel();
        $this->congeModel = new CongeModel();
        $this->departementModel = new DepartementModel();
        $this->soldeModel = new SoldeModel();
        $this->typeCongeModel = new TypeCongeModel();
        $this->roleModel = new RoleModel();
    }

    public function dashboard()
    {
        $employesActifs = $this->employeModel->where('actif', 1)->countAllResults();
        $demandesEnAttente = $this->congeModel->where('statut', 'en_attente')->countAllResults();
        $demandesApprouvees = $this->congeModel->where('statut', 'approuve')->countAllResults();
        $departements = $this->departementModel->countAllResults();

        $recentDemandes = $this->congeModel->getCongesWithSoldes([], 5);

        return view('admin/dashboard', [
            'metrics' => [
                'employesActifs' => $employesActifs,
                'demandesEnAttente' => $demandesEnAttente,
                'demandesApprouvees' => $demandesApprouvees,
                'departements' => $departements,
                'absents' => 0,
            ],
            'recentDemandes' => $recentDemandes,
        ]);
    }

    public function employes()
    {
        $employes = $this->employeModel
            ->select('employes.*, departements.nom as departement, roles.nom as role_name')
            ->join('departements', 'departements.id = employes.id_departement', 'left')
            ->join('roles', 'roles.id = employes.id_role', 'left')
            ->orderBy('employes.id', 'desc')
            ->findAll();

        $departements = $this->departementModel->findAll();
        $roles = $this->roleModel->findAll();

        $annee = (int) date('Y');
        $soldes = $this->soldeModel
            ->select('id_employe, SUM(jours_attribues) as total_attribues, SUM(jours_pris) as total_pris')
            ->where('annee', $annee)
            ->groupBy('id_employe')
            ->findAll();

        $soldesMap = [];
        foreach ($soldes as $solde) {
            $soldesMap[$solde['id_employe']] = $solde;
        }

        return view('admin/employes', [
            'employes' => $employes,
            'departements' => $departements,
            'roles' => $roles,
            'soldesMap' => $soldesMap,
        ]);
    }

    public function storeEmploye()
    {
        $password = (string) $this->request->getPost('password');
        $idDepartement = (string) $this->request->getPost('id_departement');
        $roleInput = (string) $this->request->getPost('id_role');
        if ($roleInput === '') {
            $roleInput = (string) $this->request->getPost('role');
        }

        $roleId = null;
        if ($roleInput !== '') {
            if (is_numeric($roleInput)) {
                $roleId = (int) $roleInput;
            } else {
                $roleRow = $this->roleModel->where('nom', $roleInput)->first();
                if ($roleRow) {
                    $roleId = (int) $roleRow['id'];
                }
            }
        }

        if (!$roleId) {
            $defaultRole = $this->roleModel->where('nom', 'employe')->first();
            $roleId = $defaultRole ? (int) $defaultRole['id'] : null;
        }

        $data = [
            'nom' => (string) $this->request->getPost('nom'),
            'prenom' => (string) $this->request->getPost('prenom'),
            'email' => (string) $this->request->getPost('email'),
            'id_role' => $roleId,
            'id_departement' => $idDepartement !== '' ? (int) $idDepartement : null,
            'date_embauche' => (string) $this->request->getPost('date_embauche') ?: date('Y-m-d'),
            'actif' => 1,
        ];

        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $employeId = $this->employeModel->insert($data, true);

        if ($employeId) {
            $annee = (int) date('Y');
            $types = $this->typeCongeModel->findAll();
            foreach ($types as $type) {
                $this->soldeModel->insert([
                    'id_employe' => $employeId,
                    'id_type_conge' => $type['id'],
                    'annee' => $annee,
                    'jours_attribues' => (int) $type['jours_annuels'],
                    'jours_pris' => 0,
                ]);
            }
        }

        session()->setFlashdata('success', 'Employe ajoute avec succes.');
        return redirect()->to('/admin/employes');
    }

    public function updateEmploye($id)
    {
        $employe = $this->employeModel->find($id);
        if (!$employe) {
            session()->setFlashdata('error', 'Employe non trouve.');
            return redirect()->to('/admin/employes');
        }

        $idDepartement = (string) $this->request->getPost('id_departement');
        $roleInput = (string) $this->request->getPost('id_role');
        $password = (string) $this->request->getPost('password');
        $email = trim((string) $this->request->getPost('email'));

        if ($email === '') {
            session()->setFlashdata('error', 'Email obligatoire.');
            return redirect()->to('/admin/employes');
        }

        $existing = $this->employeModel->where('email', $email)->first();
        if ($existing && (int) $existing['id'] !== (int) $id) {
            session()->setFlashdata('error', 'Cet email existe deja pour un autre employe.');
            return redirect()->to('/admin/employes');
        }

        $roleId = null;
        if ($roleInput !== '' && is_numeric($roleInput)) {
            $roleId = (int) $roleInput;
        }

        if (!$roleId) {
            $defaultRole = $this->roleModel->where('nom', 'employe')->first();
            $roleId = $defaultRole ? (int) $defaultRole['id'] : null;
        }

        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'email' => $email,
            'id_role' => $roleId,
            'id_departement' => $idDepartement !== '' ? (int) $idDepartement : null,
            'date_embauche' => (string) $this->request->getPost('date_embauche') ?: $employe['date_embauche'],
        ];

        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->employeModel->update($id, $data);
        session()->setFlashdata('success', 'Employe modifie avec succes.');
        return redirect()->to('/admin/employes');
    }

    public function deleteEmploye($id)
    {
        $employe = $this->employeModel->find($id);
        if (!$employe) {
            session()->setFlashdata('error', 'Employe non trouve.');
            return redirect()->to('/admin/employes');
        }

        $hasConges = $this->congeModel
            ->groupStart()
            ->where('id_employe', $id)
            ->orWhere('traite_par', $id)
            ->groupEnd()
            ->countAllResults() > 0;

        $hasSoldes = $this->soldeModel->where('id_employe', $id)->countAllResults() > 0;

        if ($hasConges || $hasSoldes) {
            session()->setFlashdata('error', 'Suppression impossible: cet employe a des conges ou des soldes lies. Desactivez-le plutot.');
            return redirect()->to('/admin/employes');
        }

        $this->employeModel->delete($id);
        session()->setFlashdata('success', 'Employe supprime avec succes.');
        return redirect()->to('/admin/employes');
    }

    public function toggleEmploye($id)
    {
        $employe = $this->employeModel->find($id);
        if ($employe) {
            $nouvelEtat = ((int) $employe['actif'] === 1) ? 0 : 1;
            $this->employeModel->update($id, ['actif' => $nouvelEtat]);
            session()->setFlashdata('success', 'Statut employe mis a jour.');
        } else {
            session()->setFlashdata('error', 'Employe non trouve.');
        }
        return redirect()->to('/admin/employes');
    }
    public function departements()
    {
        $departements = $this->departementModel->findAll();
        return view('admin/departements', [
            'departements' => $departements,
        ]);
    }

    public function typesConges()
    {
        $types = $this->typeCongeModel->findAll();
        return view('admin/types_conges', [
            'types' => $types,
        ]);
    }

    // Departements CRUD
    public function storeDepartement()
    {
        $nom = trim((string) $this->request->getPost('nom'));
        $description = trim((string) $this->request->getPost('description'));
        if ($nom === '') {
            session()->setFlashdata('error', 'Le nom du département est requis.');
            return redirect()->to('/admin/departements');
        }
        $this->departementModel->insert(['nom' => $nom, 'description' => $description]);
        session()->setFlashdata('success', 'Département ajouté.');
        return redirect()->to('/admin/departements');
    }

    public function updateDepartement($id)
    {
        $departement = $this->departementModel->find($id);
        if (!$departement) {
            session()->setFlashdata('error', 'Département introuvable.');
            return redirect()->to('/admin/departements');
        }
        $nom = trim((string) $this->request->getPost('nom'));
        $description = trim((string) $this->request->getPost('description'));
        if ($nom === '') {
            session()->setFlashdata('error', 'Le nom du département est requis.');
            return redirect()->to('/admin/departements');
        }
        $this->departementModel->update($id, ['nom' => $nom, 'description' => $description]);
        session()->setFlashdata('success', 'Département modifié.');
        return redirect()->to('/admin/departements');
    }

    public function deleteDepartement($id)
    {
        $departement = $this->departementModel->find($id);
        if (!$departement) {
            session()->setFlashdata('error', 'Département introuvable.');
            return redirect()->to('/admin/departements');
        }
        // Simple safety: check if any employees assigned
        $employeCount = $this->employeModel->where('id_departement', $id)->countAllResults();
        if ($employeCount > 0) {
            session()->setFlashdata('error', 'Impossible de supprimer: des employés appartiennent à ce département.');
            return redirect()->to('/admin/departements');
        }
        $this->departementModel->delete($id);
        session()->setFlashdata('success', 'Département supprimé.');
        return redirect()->to('/admin/departements');
    }

    // Types de congé CRUD
    public function storeTypeConge()
    {
        $libelle = trim((string) $this->request->getPost('libelle'));
        $jours = (int) $this->request->getPost('jours_annuels');
        $deductible = $this->request->getPost('deductible') ? 1 : 0;
        if ($libelle === '') {
            session()->setFlashdata('error', 'Libellé requis.');
            return redirect()->to('/admin/types_conges');
        }
        $this->typeCongeModel->insert(['libelle' => $libelle, 'jours_annuels' => $jours, 'deductible' => $deductible]);
        session()->setFlashdata('success', 'Type de congé ajouté.');
        return redirect()->to('/admin/types_conges');
    }

    public function updateTypeConge($id)
    {
        $type = $this->typeCongeModel->find($id);
        if (!$type) {
            session()->setFlashdata('error', 'Type introuvable.');
            return redirect()->to('/admin/types_conges');
        }
        $libelle = trim((string) $this->request->getPost('libelle'));
        $jours = (int) $this->request->getPost('jours_annuels');
        $deductible = $this->request->getPost('deductible') ? 1 : 0;
        if ($libelle === '') {
            session()->setFlashdata('error', 'Libellé requis.');
            return redirect()->to('/admin/types_conges');
        }
        $this->typeCongeModel->update($id, ['libelle' => $libelle, 'jours_annuels' => $jours, 'deductible' => $deductible]);
        session()->setFlashdata('success', 'Type de congé modifié.');
        return redirect()->to('/admin/types_conges');
    }

    public function deleteTypeConge($id)
    {
        $type = $this->typeCongeModel->find($id);
        if (!$type) {
            session()->setFlashdata('error', 'Type introuvable.');
            return redirect()->to('/admin/types_conges');
        }
        // Safety: check if any soldes or conges reference this type
        $soldesCount = $this->soldeModel->where('id_type_conge', $id)->countAllResults();
        $congesCount = $this->congeModel->where('id_type_conge', $id)->countAllResults();
        if ($soldesCount > 0 || $congesCount > 0) {
            session()->setFlashdata('error', 'Impossible de supprimer: ce type est utilisé dans des soldes ou demandes.');
            return redirect()->to('/admin/types_conges');
        }
        $this->typeCongeModel->delete($id);
        session()->setFlashdata('success', 'Type de congé supprimé.');
        return redirect()->to('/admin/types_conges');
    }

    public function soldes()
    {
        session()->setFlashdata('info', 'La gestion des soldes arrive bientot.');
        return redirect()->to('/admin/employes');
    }

    public function chargerdonnees($annee)
    {
        $congesCount = $this->congeModel->getCongesParMois($annee);


        $mois = array_fill(0, 12, 0);

        foreach ($congesCount as $row) {
            $index = (int)$row['mois'] - 1;
            $mois[$index] = (int)$row['nb'];
        }

        return $this->response->setJSON([
            'valeurs' => $mois
        ]);

    }
}
