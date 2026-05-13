<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$flashSuccess = session()->getFlashdata('success');
$flashError = session()->getFlashdata('error');
$flashSuccess = is_string($flashSuccess) ? $flashSuccess : null;
$flashError = is_string($flashError) ? $flashError : null;
$typesConge = $typesConge ?? [];
$soldes = $soldes ?? [];
$soldesByType = $soldesByType ?? [];
?>
<div class="app-wrap">

  <?= $this->include('partials/sidebar_employe') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Nouvelle demande de congé</div>
        <div class="topbar-breadcrumb">
          <a href="<?= base_url('employe/dashboard') ?>">Accueil</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande
        </div>
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

      <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start" class="form-layout">

        <!-- Formulaire principal -->
        <div>
          <div class="form-section">
            <h3>Détails de la demande</h3>

            <form method="post" action="<?= base_url('employe/store') ?>">
                <div class="f-group" style="margin-bottom:1rem">
                <label class="f-label">Type de congé <span style="color:var(--danger)">*</span></label>
                <select name="type_conge" class="f-select">
                    <option value="">-- Choisir un type --</option>
                    <?php foreach ($typesConge as $type): ?>
                      <?php
                        $solde = $soldesByType[$type['id']] ?? null;
                        $restant = $solde ? ((int) $solde['jours_attribues'] - (int) $solde['jours_pris']) : null;
                        $label = $type['libelle'] ?? 'Type';
                        $suffix = $restant !== null ? ' (' . $restant . ' j restants)' : '';
                      ?>
                      <option value="<?= esc((string) $type['id']) ?>" <?= set_select('type_conge', (string) $type['id']) ?>><?= esc($label . $suffix) ?></option>
                    <?php endforeach; ?>
                </select>
                <!-- Erreur validation CI4 -->
                <!-- <div class="f-error"><i class="bi bi-exclamation-circle"></i> Ce champ est requis.</div> -->
                </div>

                <div class="form-grid-2" style="margin-bottom:1rem">
                <div class="f-group">
                    <label class="f-label">Date de début <span style="color:var(--danger)">*</span></label>
                    <input type="date" name="date_debut" class="f-input" value="<?= esc(set_value('date_debut')) ?>"/>
                </div>
                <div class="f-group">
                    <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
                    <input type="date" name="date_fin" class="f-input" value="<?= esc(set_value('date_fin')) ?>"/>
                </div>
                </div>

                <!-- Calcul automatique côté PHP (affiché après soumission ou en JS) -->
                <div class="f-computed">
                  <div class="f-computed-num">—</div>
                  <div class="f-computed-label">Le nombre de jours sera calcule a la soumission.</div>
                </div>

                <div class="f-group" style="margin-bottom:1rem">
                <label class="f-label">Motif (optionnel)</label>
                <textarea name="motif" class="f-textarea" placeholder="Précisez le motif de votre demande si nécessaire..."><?= esc(set_value('motif')) ?></textarea>
                <div class="f-hint">Le motif est visible par le responsable RH.</div>
                </div>

                <div class="form-actions">
                <button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la demande</button>
                <a href="<?= base_url('employe/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
                </div>
            </form>
          </div>
        </div>

        <!-- Panneau latéral : solde & règles -->
        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
              <?php if (!empty($soldes)): ?>
                <?php foreach ($soldes as $solde): ?>
                  <?php
                    $attribues = (int) $solde['jours_attribues'];
                    $pris = (int) $solde['jours_pris'];
                    $restant = max(0, $attribues - $pris);
                    $percent = $attribues > 0 ? (int) round(($restant / $attribues) * 100) : 0;
                    $fillClass = $percent <= 20 ? 'warn' : '';
                  ?>
                  <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                      <span style="font-size:.8rem;color:var(--ink)"><?= esc((string) ($solde['libelle'] ?? 'Type')) ?></span>
                      <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500"><?= esc((string) $restant) ?> j</span>
                    </div>
                    <div class="solde-bar"><div class="solde-fill <?= esc($fillClass) ?>" style="width:<?= esc((string) min(100, $percent)) ?>%"></div></div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="empty">
                  <i class="bi bi-piggy-bank"></i>
                  <p>Aucun solde configure.</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="flash flash-info" style="margin:0">
            <i class="bi bi-info-circle-fill"></i>
            <span style="font-size:.8rem">Le solde est déduit uniquement à l'approbation de votre responsable.</span>
          </div>
          <div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:.85rem 1rem">
            <div style="font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.5rem"><i class="bi bi-clipboard-check" style="color:var(--forest);margin-right:5px"></i>Rappel des règles</div>
            <ul style="margin:0;padding-left:1rem;font-size:.75rem;color:var(--muted);line-height:1.7">
              <li>Préavis minimum : 48h avant la date de début</li>
              <li>Pas de chevauchement avec une demande en cours</li>
              <li>Solde insuffisant = demande refusée automatiquement</li>
            </ul>
          </div>
        </div>

      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>
<?= $this->endSection() ?>
