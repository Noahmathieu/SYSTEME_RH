<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$flashSuccess = session()->getFlashdata('success');
$flashError = session()->getFlashdata('error');
$departements = $departements ?? [];
?>
<div class="app-wrap">
  <?= $this->include('partials/sidebar_admin') ?>
  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Départements</div>
        <div class="topbar-breadcrumb"><a href="<?= base_url('admin/dashboard') ?>">Admin</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Départements</div>
      </div>
    </div>
    <div class="content">
      <?php if ($flashSuccess): ?><div class="flash flash-success"><?= esc($flashSuccess) ?></div><?php endif; ?>
      <?php if ($flashError): ?><div class="flash flash-error"><?= esc($flashError) ?></div><?php endif; ?>

      <div class="form-section">
        <h3>Ajouter un département</h3>
        <form method="post" action="<?= base_url('admin/departements') ?>">
          <div class="form-grid-2">
            <div class="f-group">
              <label class="f-label">Nom</label>
              <input type="text" name="nom" class="f-input" required />
            </div>
            <div class="f-group">
              <label class="f-label">Description</label>
              <input type="text" name="description" class="f-input" />
            </div>
          </div>
          <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> Créer</button>
          </div>
        </form>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Tous les départements</h3></div>
        <table class="tbl">
          <thead><tr><th>Nom</th><th>Description</th><th>Actions</th></tr></thead>
          <tbody>
            <?php if (!empty($departements)): ?>
              <?php foreach ($departements as $d): ?>
                <tr>
                  <td><?= esc((string) $d['nom']) ?></td>
                  <td class="td-muted"><?= esc((string) ($d['description'] ?? '')) ?></td>
                  <td>
                    <div class="action-btns">
                      <button class="btn-sm btn-edit js-open-edit" type="button" data-id="<?= esc((string) $d['id']) ?>">Modifier</button>
                      <form method="post" action="<?= base_url('admin/departements/' . $d['id'] . '/delete') ?>" onsubmit="return confirm('Supprimer ce département ?');">
                        <button class="btn-sm btn-refuse" type="submit">Supprimer</button>
                      </form>
                    </div>
                  </td>
                </tr>
                <tr id="edit-dept-<?= esc((string) $d['id']) ?>" style="display:none;background:var(--cream)">
                  <td colspan="3">
                    <form method="post" action="<?= base_url('admin/departements/' . $d['id'] . '/update') ?>">
                      <div class="form-grid-2">
                        <div class="f-group">
                          <label class="f-label">Nom</label>
                          <input type="text" name="nom" class="f-input" value="<?= esc((string) $d['nom']) ?>" required />
                        </div>
                        <div class="f-group">
                          <label class="f-label">Description</label>
                          <input type="text" name="description" class="f-input" value="<?= esc((string) ($d['description'] ?? '')) ?>" />
                        </div>
                      </div>
                      <div class="form-actions">
                        <button class="btn-forest" type="submit">Enregistrer</button>
                        <button type="button" class="btn-secondary js-close-edit" data-id="<?= esc((string) $d['id']) ?>">Annuler</button>
                      </div>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="3"><div class="empty"><i class="bi bi-building"></i><p>Aucun département.</p></div></td></tr>
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
  document.querySelectorAll('[id^="edit-dept-"]').forEach(r => r.style.display='none');
  const row = document.getElementById('edit-dept-'+id);
  if (row) row.style.display = 'table-row';
}));
document.querySelectorAll('.js-close-edit').forEach(btn => btn.addEventListener('click', e => {
  const id = e.currentTarget.getAttribute('data-id');
  const row = document.getElementById('edit-dept-'+id);
  if (row) row.style.display = 'none';
}));
</script>

<?= $this->endSection() ?>