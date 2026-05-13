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
        session()->setFlashdata('info', 'La gestion des departements arrive bientot.');
        return redirect()->to('/admin/employes');
    }

    public function typesConges()
    {
        session()->setFlashdata('info', 'La gestion des types de conge arrive bientot.');
        return redirect()->to('/admin/employes');
    }

    public function soldes()
    {
        session()->setFlashdata('info', 'La gestion des soldes arrive bientot.');
        return redirect()->to('/admin/employes');
    }
    
}