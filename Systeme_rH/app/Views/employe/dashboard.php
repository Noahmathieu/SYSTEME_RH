<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$statutClasses = [
  'en_attente' => 's-attente',
  'approuve' => 's-approuvee',
  'refuse' => 's-refusee',
  'annule' => 's-annulee',
];
$flashSuccess = session()->getFlashdata('success');
$flashError = session()->getFlashdata('error');
$flashSuccess = is_string($flashSuccess) ? $flashSuccess : null;
$flashError = is_string($flashError) ? $flashError : null;
$stats = $stats ?? [];
$soldes = $soldes ?? [];
$demandes = $demandes ?? [];
$totalAttribues = $totalAttribues ?? 0;
$totalRestant = $totalRestant ?? 0;
?>
<div class="app-wrap">

  <!-- SIDEBAR EMPLOYÉ -->
  <?= $this->include('partials/sidebar_employe') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Tableau de bord</div>
        <div class="topbar-breadcrumb">Accueil</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= base_url('employe/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
          <i class="bi bi-plus-lg"></i> Nouvelle demande
        </a>
      </div>
    </div>

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

      <!-- Métriques -->
      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val"><?= esc((string) ($stats['en_attente'] ?? 0)) ?></div>
          <div class="metric-label">En attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
          <div class="metric-val"><?= esc((string) ($stats['approuve'] ?? 0)) ?></div>
          <div class="metric-label">Approuvées</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val"><?= esc((string) $totalRestant) ?></div>
          <div class="metric-label">Jours restants</div>
          <div class="metric-sub">sur <?= esc((string) $totalAttribues) ?> cette année</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
          <div class="metric-val"><?= esc((string) ($stats['refuse'] ?? 0)) ?></div>
          <div class="metric-label">Refusée</div>
        </div>
      </div>

      <!-- Soldes de congés -->
      <div class="data-card">
        <div class="data-card-head"><h3>Mes soldes de congés — <?= esc(date('Y')) ?></h3></div>
        <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <?php if (!empty($soldes)): ?>
            <?php foreach ($soldes as $solde): ?>
              <?php
                $attribues = (int) $solde['jours_attribues'];
                $pris = (int) $solde['jours_pris'];
                $restant = max(0, $attribues - $pris);
                $percent = $attribues > 0 ? (int) round(($restant / $attribues) * 100) : 0;
                $fillClass = $percent <= 20 ? 'danger' : ($percent <= 40 ? 'warn' : '');
              ?>
              <div class="solde-card" style="margin:0">
                <div class="solde-header">
                  <span class="solde-type"><?= esc((string) ($solde['libelle'] ?? 'Type')) ?></span>
                  <span class="solde-nums"><strong><?= esc((string) $restant) ?></strong> / <?= esc((string) $attribues) ?> j</span>
                </div>
                <div class="solde-bar"><div class="solde-fill <?= esc($fillClass) ?>" style="width:<?= esc((string) min(100, $percent)) ?>%"></div></div>
                <div class="solde-label"><?= esc((string) $restant) ?> jours restants · <?= esc((string) $pris) ?> pris</div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty">
              <i class="bi bi-piggy-bank"></i>
              <p>Aucun solde disponible pour le moment.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Dernières demandes -->
      <div class="data-card">
        <div class="data-card-head">
          <h3>Mes dernières demandes</h3>
          <a href="<?= base_url('employe/conges') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Du</th><th>Au</th><th>Durée</th><th>Statut</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php if (!empty($demandes)): ?>
              <?php foreach ($demandes as $demande): ?>
                <?php $statut = $demande['statut'] ?? 'en_attente'; ?>
                <tr>
                  <td><span class="type-badge"><?= esc((string) ($demande['type_conge'] ?? 'Type')) ?></span></td>
                  <td class="td-muted"><?= esc((string) ($demande['date_debut'] ?? '')) ?></td>
                  <td class="td-muted"><?= esc((string) ($demande['date_fin'] ?? '')) ?></td>
                  <td class="td-mono"><?= esc((string) ($demande['nb_jours'] ?? 0)) ?> j</td>
                  <td><span class="statut <?= esc((string) ($statutClasses[$statut] ?? 's-attente')) ?>"><?= esc((string) $statut) ?></span></td>
                  <td>
                    <?php if ($statut === 'en_attente'): ?>
                      <form method="post" action="<?= base_url('employe/conges/' . $demande['id'] . '/cancel') ?>" style="display:inline">
                        <button class="btn-sm btn-cancel" type="submit"><i class="bi bi-x"></i> Annuler</button>
                      </form>
                    <?php else: ?>
                      <span class="td-muted" style="font-size:.75rem">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6">
                  <div class="empty">
                    <i class="bi bi-calendar3"></i>
                    <p>Aucune demande pour le moment.</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Projet CodeIgniter 4</div>
  </div>

</div>
<?= $this->endSection() ?>
