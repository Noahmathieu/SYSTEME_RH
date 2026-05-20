<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$flashSuccess = session()->getFlashdata('success');
$flashError = session()->getFlashdata('error');
$types = $types ?? [];
?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_admin') ?>
  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Types de congé</div>
        <div class="topbar-breadcrumb"><a href="<?= base_url('admin/dashboard') ?>">Admin</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Types de congé</div>
      </div>
    </div>
    <div class="content">
      <?php if ($flashSuccess): ?><div class="flash flash-success"><?= esc($flashSuccess) ?></div><?php endif; ?>
      <?php if ($flashError): ?><div class="flash flash-error"><?= esc($flashError) ?></div><?php endif; ?>

      <div class="form-section">
        <h3>Ajouter un type de congé</h3>
        <form method="post" action="<?= base_url('admin/types_conges') ?>">
          <div class="form-grid-2">
            <div class="f-group">
              <label class="f-label">Libellé</label>
              <input type="text" name="libelle" class="f-input" required />
            </div>
            <div class="f-group">
              <label class="f-label">Jours annuels</label>
              <input type="number" name="jours_annuels" class="f-input" value="0" min="0" />
            </div>
            <div class="f-group">
              <label class="f-label">Déductible</label>
              <select name="deductible" class="f-select"><option value="1">Oui</option><option value="0">Non</option></select>
            </div>
          </div>
          <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> Créer</button>
          </div>
        </form>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Tous les types</h3></div>
        <table class="tbl">
          <thead><tr><th>Libellé</th><th>Jours</th><th>Déductible</th><th>Actions</th></tr></thead>
          <tbody>
            <?php if (!empty($types)): ?>
              <?php foreach ($types as $t): ?>
                <tr>
                  <td><?= esc((string) $t['libelle']) ?></td>
                  <td class="td-mono"><?= esc((string) $t['jours_annuels']) ?></td>
                  <td><?= ((int) $t['deductible'] === 1) ? 'Oui' : 'Non' ?></td>
                  <td>
                    <div class="action-btns">
                      <button class="btn-sm btn-edit js-open-edit" type="button" data-id="<?= esc((string) $t['id']) ?>">Modifier</button>
                      <form method="post" action="<?= base_url('admin/types_conges/' . $t['id'] . '/delete') ?>" onsubmit="return confirm('Supprimer ce type ?');">
                        <button class="btn-sm btn-refuse" type="submit">Supprimer</button>
                      </form>
                    </div>
                  </td>
                </tr>
                <tr id="edit-type-<?= esc((string) $t['id']) ?>" style="display:none;background:var(--cream)">
                  <td colspan="4">
                    <form method="post" action="<?= base_url('admin/types_conges/' . $t['id'] . '/update') ?>">
                      <div class="form-grid-2">
                        <div class="f-group">
                          <label class="f-label">Libellé</label>
                          <input type="text" name="libelle" class="f-input" value="<?= esc((string) $t['libelle']) ?>" required />
                        </div>
                        <div class="f-group">
                          <label class="f-label">Jours annuels</label>
                          <input type="number" name="jours_annuels" class="f-input" value="<?= esc((string) $t['jours_annuels']) ?>" min="0" />
                        </div>
                        <div class="f-group">
                          <label class="f-label">Déductible</label>
                          <select name="deductible" class="f-select"><option value="1" <?= ((int)$t['deductible']===1)?'selected':'' ?>>Oui</option><option value="0" <?= ((int)$t['deductible']===0)?'selected':'' ?>>Non</option></select>
                        </div>
                      </div>
                      <div class="form-actions">
                        <button class="btn-forest" type="submit">Enregistrer</button>
                        <button type="button" class="btn-secondary js-close-edit" data-id="<?= esc((string) $t['id']) ?>">Annuler</button>
                      </div>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="4"><div class="empty"><i class="bi bi-tags"></i><p>Aucun type enregistre.</p></div></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
    <div class="footer-app">2025 TechMada RH</div>
  </div>
</div>

<script>
document.querySelectorAll('.js-open-edit').forEach(btn => btn.addEventListener('click', e => {
  const id = e.currentTarget.getAttribute('data-id');
  if (!id) return;
  document.querySelectorAll('[id^="edit-type-"]').forEach(r => r.style.display='none');
  const row = document.getElementById('edit-type-'+id);
  if (row) row.style.display = 'table-row';
}));
document.querySelectorAll('.js-close-edit').forEach(btn => btn.addEventListener('click', e => {
  const id = e.currentTarget.getAttribute('data-id');
  const row = document.getElementById('edit-type-'+id);
  if (row) row.style.display = 'none';
}));
</script>

<?= $this->endSection() ?>