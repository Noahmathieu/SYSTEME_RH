<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<?php
$flashSuccess = session()->getFlashdata('success');
$flashError = session()->getFlashdata('error');
$flashInfo = session()->getFlashdata('info');
$roles = $roles ?? [];
$departements = $departements ?? [];
$flashSuccess = is_string($flashSuccess) ? $flashSuccess : null;
$flashError = is_string($flashError) ? $flashError : null;
$flashInfo = is_string($flashInfo) ? $flashInfo : null;
?>
<div class="app-wrap">

  <?= $this->include('partials/sidebar_admin') ?>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Gestion des employés</div>
        <div class="topbar-breadcrumb"><a href="<?= base_url('admin/dashboard') ?>">Admin</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Employés</div>
      </div>
      <div class="topbar-actions">
        <a href="#" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-person-plus"></i> Ajouter</a>
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
      <?php if ($flashInfo): ?>
      <div class="flash flash-info">
        <i class="bi bi-info-circle-fill"></i>
        <?= esc($flashInfo) ?>
      </div>
      <?php endif; ?>

      <!-- Formulaire ajout -->
      <div class="form-section">
        <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Ajouter un employé</h3>
        <form method="post" action="<?= base_url('admin/employes') ?>">
          <div class="form-grid-2" style="margin-bottom:1rem">
            <div class="f-group">
              <label class="f-label">Prénom</label>
              <input type="text" name="prenom" class="f-input" placeholder="Jean" value="<?= esc(set_value('prenom')) ?>"/>
            </div>
            <div class="f-group">
              <label class="f-label">Nom</label>
              <input type="text" name="nom" class="f-input" placeholder="Rakoto" value="<?= esc(set_value('nom')) ?>"/>
            </div>
            <div class="f-group">
              <label class="f-label">Email</label>
              <input type="email" name="email" class="f-input" placeholder="jean.rakoto@techmada.mg" value="<?= esc(set_value('email')) ?>"/>
            </div>
            <div class="f-group">
              <label class="f-label">Mot de passe initial</label>
              <input type="password" name="password" class="f-input" placeholder="A communiquer a l'employe"/>
            </div>
            <div class="f-group">
              <label class="f-label">Département</label>
              <select name="id_departement" class="f-select">
                <option value="">-- Choisir --</option>
                <?php foreach ($departements as $departement): ?>
                  <option value="<?= esc((string) $departement['id']) ?>" <?= set_select('id_departement', (string) $departement['id']) ?>><?= esc((string) $departement['nom']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">Rôle</label>
              <select name="id_role" class="f-select">
                <option value="">-- Choisir --</option>
                <?php if (!empty($roles)): ?>
                  <?php foreach ($roles as $role): ?>
                    <option value="<?= esc((string) $role['id']) ?>" <?= set_select('id_role', (string) $role['id']) ?>><?= esc((string) $role['nom']) ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="1">admin</option>
                  <option value="2">rh</option>
                  <option value="3">employe</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">Date d'embauche</label>
              <input type="date" name="date_embauche" class="f-input" value="<?= esc(set_value('date_embauche')) ?>"/>
            </div>
          </div>
        <div class="flash flash-info" style="margin-bottom:1rem">
          <i class="bi bi-info-circle-fill"></i>
          <span style="font-size:.82rem">Les soldes de congés seront initialisés automatiquement selon les types de congé configurés.</span>
        </div>
          <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> Créer l'employé</button>
            <button class="btn-secondary" type="reset">Réinitialiser</button>
          </div>
        </form>
      </div>

      <!-- Liste employés -->
      <div class="data-card">
        <div class="data-card-head">
          <h3>Tous les employés</h3>
          <div style="display:flex;gap:6px">
            <input type="text" class="f-input" placeholder="Rechercher..." style="width:200px;padding:6px 10px;font-size:.8rem"/>
            <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
              <option>Tous les depts</option>
              <option>IT</option>
              <option>Finance</option>
            </select>
          </div>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Département</th><th>Rôle</th><th>Embauche</th><th>Statut</th><th>Solde annuel</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php if (!empty($employes)): ?>
              <?php foreach ($employes as $employe): ?>
                <?php
                  $initials = strtoupper(substr($employe['prenom'] ?? 'X', 0, 1) . substr($employe['nom'] ?? 'X', 0, 1));
                  $solde = $soldesMap[$employe['id']] ?? null;
                  $attribues = $solde ? (int) $solde['total_attribues'] : 0;
                  $pris = $solde ? (int) $solde['total_pris'] : 0;
                  $restant = max(0, $attribues - $pris);
                ?>
                <tr <?= ((int) $employe['actif'] === 0) ? 'style="opacity:.5"' : '' ?>>
                  <td>
                    <div class="profile-row">
                      <div class="avatar av-green" style="width:32px;height:32px;font-size:.68rem"><?= esc($initials) ?></div>
                      <div class="profile-info"><div class="pname"><?= esc((string) trim(($employe['prenom'] ?? '') . ' ' . ($employe['nom'] ?? ''))) ?></div><div class="pdept"><?= esc((string) ($employe['email'] ?? '')) ?></div></div>
                    </div>
                  </td>
                  <td class="td-muted"><?= esc((string) ($employe['departement'] ?? '—')) ?></td>
                  <td><span class="type-badge" style="background:#f1efe8;color:#444441"><?= esc((string) ($employe['role_name'] ?? 'employe')) ?></span></td>
                  <td class="td-muted td-mono" style="font-size:.78rem"><?= esc((string) ($employe['date_embauche'] ?? '—')) ?></td>
                  <td><span class="statut <?= ((int) $employe['actif'] === 1) ? 's-approuvee' : 's-annulee' ?>" style="font-size:.68rem"><?= ((int) $employe['actif'] === 1) ? 'actif' : 'inactif' ?></span></td>
                  <td>
                    <?php if ($attribues > 0): ?>
                      <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest)"><?= esc((string) $restant) ?> / <?= esc((string) $attribues) ?> j</span>
                    <?php else: ?>
                      <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--muted)">— / — j</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="action-btns">
                      <form method="post" action="<?= base_url('admin/employes/' . $employe['id'] . '/toggle') ?>">
                        <button class="btn-sm <?= ((int) $employe['actif'] === 1) ? 'btn-del' : 'btn-view' ?>" type="submit">
                          <i class="bi <?= ((int) $employe['actif'] === 1) ? 'bi-slash-circle' : 'bi-arrow-counterclockwise' ?>"></i>
                          <?= ((int) $employe['actif'] === 1) ? 'Desactiver' : 'Reactiver' ?>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7">
                  <div class="empty">
                    <i class="bi bi-people"></i>
                    <p>Aucun employe enregistre.</p>
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
