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
$demandes = $demandes ?? [];
?>
<div class="app-wrap">

  <?= $this->include('partials/sidebar_rh') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Demandes à traiter</div>
        <div class="topbar-breadcrumb"><a href="<?= base_url('rh/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Demandes</div>
      </div>
      <div class="topbar-actions">
        <span style="font-size:.8rem;color:var(--muted);background:var(--warn-bg);border:1px solid var(--warn-br);border-radius:6px;padding:5px 10px;display:flex;align-items:center;gap:5px;color:var(--warn)">
          <i class="bi bi-hourglass-split"></i> <?= esc((string) ($stats['en_attente'] ?? 0)) ?> en attente
        </span>
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

      <!-- Filtre -->
      <div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
        <?php $totalDemandes = array_sum($stats); ?>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--forest);background:var(--forest);color:var(--white);cursor:pointer">Tous (<?= esc((string) $totalDemandes) ?>)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">En attente (<?= esc((string) ($stats['en_attente'] ?? 0)) ?>)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">Approuvées (<?= esc((string) ($stats['approuve'] ?? 0)) ?>)</button>
        <button style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">Refusées (<?= esc((string) ($stats['refuse'] ?? 0)) ?>)</button>
        <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto;margin-left:auto">
          <option>Tous les départements</option>
          <?php foreach ($departements as $dept): ?>
            <option><?= esc((string) ($dept['libelle'] ?? '')) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Toutes les demandes</h3></div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Solde dispo</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php if (!empty($demandes)): ?>
              <?php foreach ($demandes as $demande): ?>
                <?php
                  $statut = $demande['statut'] ?? 'en_attente';
                  $soldeRestant = isset($demande['solde_restant']) ? (int) $demande['solde_restant'] : null;
                  $soldeInsuffisant = $soldeRestant !== null && $soldeRestant < (int) $demande['nb_jours'];
                  $initials = strtoupper(substr($demande['prenom'] ?? 'X', 0, 1) . substr($demande['nom'] ?? 'X', 0, 1));
                ?>
                <tr>
                  <td>
                    <div class="profile-row">
                      <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem"><?= esc($initials) ?></div>
                      <div class="profile-info">
                        <div class="pname"><?= esc(trim(($demande['prenom'] ?? '') . ' ' . ($demande['nom'] ?? ''))) ?></div>
                        <div class="pdept"><?= esc((string) ($demande['email'] ?? '')) ?></div>
                      </div>
                    </div>
                  </td>
                  <td><span class="type-badge"><?= esc((string) ($demande['type_conge'] ?? 'Type')) ?></span></td>
                  <td class="td-muted" style="font-size:.8rem"><?= esc((string) ($demande['date_debut'] ?? '')) ?> – <?= esc((string) ($demande['date_fin'] ?? '')) ?></td>
                  <td class="td-mono"><?= esc((string) ($demande['nb_jours'] ?? 0)) ?> j</td>
                  <td>
                    <?php if ($soldeRestant !== null): ?>
                      <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:<?= $soldeInsuffisant ? 'var(--warn)' : 'var(--success)' ?>;font-weight:500"><?= esc((string) $soldeRestant) ?> j</span>
                      <span style="font-size:.72rem;color:<?= $soldeInsuffisant ? 'var(--danger)' : 'var(--muted)' ?>"><?= $soldeInsuffisant ? ' insuffisant' : ' dispo' ?></span>
                    <?php else: ?>
                      <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--muted)">—</span>
                    <?php endif; ?>
                  </td>
                  <td><span class="statut <?= esc((string) ($statutClasses[$statut] ?? 's-attente')) ?>"><?= esc((string) $statut) ?></span></td>
                  <td>
                    <?php if ($statut === 'en_attente'): ?>
                      <div class="action-btns">
                        <form method="post" action="<?= base_url('rh/demandes/' . $demande['id'] . '/status') ?>">
                          <input type="hidden" name="status" value="approuve" />
                          <button class="btn-sm btn-approve" type="submit" <?= $soldeInsuffisant ? 'disabled style="opacity:.4;cursor:not-allowed"' : '' ?>><i class="bi bi-check-lg"></i> Approuver</button>
                        </form>
                        <form method="post" action="<?= base_url('rh/demandes/' . $demande['id'] . '/status') ?>">
                          <input type="hidden" name="status" value="refuse" />
                          <button class="btn-sm btn-refuse" type="submit"><i class="bi bi-x-lg"></i> Refuser</button>
                        </form>
                      </div>
                    <?php else: ?>
                      <span class="td-muted" style="font-size:.75rem">Traite par <?= esc((string) ($demande['traite_par'] ?? 'RH')) ?></span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7">
                  <div class="empty">
                    <i class="bi bi-inbox"></i>
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
