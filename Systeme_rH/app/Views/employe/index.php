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
$demandes = $demandes ?? [];
?>
<div class="app-wrap">

  <?= $this->include('partials/sidebar_employe') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mes demandes de congé</div>
        <div class="topbar-breadcrumb"><a href="<?= base_url('employe/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mes demandes</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= base_url('employe/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
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
      <div class="data-card">
        <div class="data-card-head">
          <h3>Toutes mes demandes</h3>
          <div style="display:flex;gap:6px">
            <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
              <option>Tous les statuts</option>
              <option>En attente</option>
              <option>Approuvée</option>
              <option>Refusée</option>
              <option>Annulée</option>
            </select>
          </div>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr>
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
                  <td class="td-muted" style="font-size:.78rem"><?= esc((string) ($demande['commentaire_rh'] ?? '—')) ?></td>
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
                <td colspan="7">
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
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
<?= $this->endSection() ?>
