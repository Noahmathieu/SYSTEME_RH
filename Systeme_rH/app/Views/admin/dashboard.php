<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$flashSuccess = session()->getFlashdata('success');
$flashError = session()->getFlashdata('error');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f7fa;
      margin: 0;
      padding: 30px;
    }

    .container {
      width: 900px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
    }

    canvas {
      margin-top: 20px;
    }
  </style>
</head>

<body>


  <div class="app-wrap">

    <?= $this->include('partials/sidebar_admin') ?>

    <div class="main">
      <div class="topbar">
        <div>
          <div class="topbar-title">Vue d'ensemble</div>
          <div class="topbar-breadcrumb">Administration</div>
        </div>
        <div class="topbar-actions">
          <a href="<?= base_url('admin/employes') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter un employé</a>
        </div>
      </div>

      <div class="container">
        <h1>Statistiques des congés mensuelles</h1>
        <select id="annee">
          <option value="2025">2025</option>
          <option value="2026">2026</option>
          <option value="2027">2027</option>
        </select>

        <canvas id="salesChart"></canvas>
        <canvas id="salesChart2"></canvas>

      </div>

      <script>
        const ctx1 = document.getElementById('salesChart');

        const chart1 = new Chart(ctx1, {
          type: 'bar',
          data: {
            labels: [
              'Janvier',
              'Février',
              'Mars',
              'Avril',
              'Mai',
              'Juin',
              'Juillet',
              'Aout',
              'Septembre',
              'Octobre',
              'Novembre',
              'Décembre'
            ],
            datasets: [{
              label: 'Conges',
              data: [],
              backgroundColor: '#3498db',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: true
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                suggestedMax: 10,
                
              }
            }
          }
        });

        const ctx2 = document.getElementById('salesChart2');

        const chart2 = new Chart(ctx2, {
          type: 'bar',
          data: {
            labels: [
              'Janvier',
              'Février',
              'Mars',
              'Avril',
              'Mai',
              'Juin',
              'Juillet',
              'Aout',
              'Septembre',
              'Octobre',
              'Novembre',
              'Décembre'
            ],
            datasets: [{
              label: 'Jours de Conges',
              data: [],
              backgroundColor: '#3498db',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: true
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                suggestedMax: 10,
                
              }
            }
          }
        });


        function chargerDonnees1(annee) {

          fetch("<?= base_url('admin/chart1/') ?>" + annee)

            .then(response => response.json())

            .then(data => {

              chart1.data.datasets[0].data = data.valeurs;
              // const max = Math.max(...data.valeurs);
              // chart.options.scales.y.suggestedMax = max + 0.5;

              chart1.update();
            });
        }
         function chargerDonnees2(annee) {

          fetch("<?= base_url('admin/chart2/') ?>" + annee)

            .then(response => response.json())

            .then(data => {

              chart2.data.datasets[0].data = data.valeurs;
              // const max = Math.max(...data.valeurs);
              // chart.options.scales.y.suggestedMax = max + 0.5;

              chart2.update();
            });
        }

        chargerDonnees1(2025);
        chargerDonnees2(2025);


        document.getElementById('annee').addEventListener('change', function() {
          chargerDonnees1(this.value);
          chargerDonnees2(this.value);

        });
      </script>


      <div class="content">
        <?php if ($flashSuccess): ?>
          <div class="flash flash-success">
            <i class="bi bi-check-circle-fill"></i>
            <?= esc($flashSuccess) ?>
          </div>
        <?php endif; ?>
        <?php if ($flashError): ?>
          <div class="flash flash-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= esc($flashError) ?>
          </div>
        <?php endif; ?>

        <!-- Métriques admin -->
        <div class="metrics">
          <div class="metric">
            <div class="metric-top">
              <div class="metric-icon mi-forest"><i class="bi bi-people"></i></div>
            </div>
            <div class="metric-val"><?= esc($metrics['employesActifs'] ?? 0) ?></div>
            <div class="metric-label">Employés actifs</div>
            <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> +2 ce mois</div>
          </div>
          <div class="metric">
            <div class="metric-top">
              <div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div>
            </div>
            <div class="metric-val"><?= esc($metrics['demandesEnAttente'] ?? 0) ?></div>
            <div class="metric-label">Demandes en attente</div>
          </div>
          <div class="metric">
            <div class="metric-top">
              <div class="metric-icon mi-green"><i class="bi bi-calendar-check"></i></div>
            </div>
            <div class="metric-val"><?= esc($metrics['demandesApprouvees'] ?? 0) ?></div>
            <div class="metric-label">Approuvées ce mois</div>
            <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> +6 vs mois dernier</div>
          </div>
          <div class="metric">
            <div class="metric-top">
              <div class="metric-icon mi-blue"><i class="bi bi-building"></i></div>
            </div>
            <div class="metric-val"><?= esc($metrics['departements'] ?? 0) ?></div>
            <div class="metric-label">Départements</div>
          </div>
          <div class="metric">
            <div class="metric-top">
              <div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div>
            </div>
            <div class="metric-val"><?= esc($metrics['absents'] ?? 0) ?></div>
            <div class="metric-label">Absents aujourd'hui</div>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">

          <!-- Demandes récentes -->
          <div class="data-card" style="margin:0">
            <div class="data-card-head">
              <h3>Demandes récentes</h3>
              <a href="<?= base_url('rh/demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
            </div>
            <table class="tbl">
              <thead>
                <tr>
                  <th>Employé</th>
                  <th>Type</th>
                  <th>Durée</th>
                  <th>Statut</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($recentDemandes)): ?>
                  <?php foreach ($recentDemandes as $demande): ?>
                    <?php
                    $statut = $demande['statut'] ?? 'en_attente';
                    $statutClass = [
                      'en_attente' => 's-attente',
                      'approuve' => 's-approuvee',
                      'refuse' => 's-refusee',
                      'annule' => 's-annulee',
                    ][$statut] ?? 's-attente';
                    $initials = strtoupper(substr($demande['prenom'] ?? 'X', 0, 1) . substr($demande['nom'] ?? 'X', 0, 1));
                    ?>
                    <tr>
                      <td>
                        <div style="display:flex;align-items:center;gap:7px">
                          <div class="avatar av-green" style="width:28px;height:28px;font-size:.62rem"><?= esc($initials) ?></div>
                          <span class="td-name" style="font-size:.84rem"><?= esc(trim(($demande['prenom'] ?? '') . ' ' . ($demande['nom'] ?? ''))) ?></span>
                        </div>
                      </td>
                      <td><span class="type-badge"><?= esc($demande['type_conge'] ?? 'Type') ?></span></td>
                      <td class="td-mono"><?= esc($demande['nb_jours'] ?? 0) ?> j</td>
                      <td><span class="statut <?= esc($statutClass) ?>"><?= esc($statut) ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4">
                      <div class="empty">
                        <i class="bi bi-inbox"></i>
                        <p>Aucune demande recente.</p>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Absents du jour + soldes critiques -->
          <div style="display:flex;flex-direction:column;gap:1rem">
            <div class="data-card" style="margin:0">
              <div class="data-card-head">
                <h3><i class="bi bi-person-slash" style="color:var(--muted);margin-right:5px"></i>Absents aujourd'hui</h3>
              </div>
              <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.6rem">
                <div style="display:flex;align-items:center;gap:8px">
                  <div class="avatar av-green" style="width:30px;height:30px;font-size:.65rem">SR</div>
                  <div>
                    <div style="font-size:.83rem;font-weight:500;color:var(--ink)">Soa Rakoto</div>
                    <div style="font-size:.72rem;color:var(--muted)">Congé annuel · retour 28/06</div>
                  </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                  <div class="avatar" style="width:30px;height:30px;font-size:.65rem;background:#993556">NR</div>
                  <div>
                    <div style="font-size:.83rem;font-weight:500;color:var(--ink)">Noro Ramarao</div>
                    <div style="font-size:.72rem;color:var(--muted)">Maladie · retour 17/06</div>
                  </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                  <div class="avatar av-amber" style="width:30px;height:30px;font-size:.65rem">KF</div>
                  <div>
                    <div style="font-size:.83rem;font-weight:500;color:var(--ink)">Ketaka Feno</div>
                    <div style="font-size:.72rem;color:var(--muted)">Congé spécial · retour 16/06</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="flash flash-warn" style="margin:0">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <span style="font-size:.8rem">2 employés ont un solde critique (≤ 2 jours). <a href="#" style="color:var(--warn);font-weight:500">Voir les soldes →</a></span>
            </div>
          </div>

        </div>

      </div>
      <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>

  </div>
  <?= $this->endSection() ?>
</body>

</html>