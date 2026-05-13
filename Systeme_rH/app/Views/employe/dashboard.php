<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
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

      <!-- Flash succès -->
      <div class="flash flash-success">
        <i class="bi bi-check-circle-fill"></i>
        Votre demande de congé a bien été soumise. Elle est en attente de validation.
      </div>

      <!-- Métriques -->
      <div class="metrics">
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
          <div class="metric-val">2</div>
          <div class="metric-label">En attente</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
          <div class="metric-val">5</div>
          <div class="metric-label">Approuvées</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
          <div class="metric-val">18</div>
          <div class="metric-label">Jours restants</div>
          <div class="metric-sub">sur 30 cette année</div>
        </div>
        <div class="metric">
          <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
          <div class="metric-val">1</div>
          <div class="metric-label">Refusée</div>
        </div>
      </div>

      <!-- Soldes de congés -->
      <div class="data-card">
        <div class="data-card-head"><h3>Mes soldes de congés — 2025</h3></div>
        <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <div class="solde-card" style="margin:0">
            <div class="solde-header">
              <span class="solde-type">Congé annuel</span>
              <span class="solde-nums"><strong>18</strong> / 30 j</span>
            </div>
            <div class="solde-bar"><div class="solde-fill" style="width:60%"></div></div>
            <div class="solde-label">18 jours restants · 12 pris</div>
          </div>
          <div class="solde-card" style="margin:0">
            <div class="solde-header">
              <span class="solde-type">Congé maladie</span>
              <span class="solde-nums"><strong>8</strong> / 10 j</span>
            </div>
            <div class="solde-bar"><div class="solde-fill" style="width:80%"></div></div>
            <div class="solde-label">8 jours restants · 2 pris</div>
          </div>
          <div class="solde-card" style="margin:0">
            <div class="solde-header">
              <span class="solde-type">Congé spécial</span>
              <span class="solde-nums"><strong>1</strong> / 5 j</span>
            </div>
            <div class="solde-bar"><div class="solde-fill warn" style="width:20%"></div></div>
            <div class="solde-label">1 jour restant · 4 pris</div>
          </div>
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
            <tr>
              <td><span class="type-badge t-annuel">Annuel</span></td>
              <td class="td-muted">16 juin 2025</td>
              <td class="td-muted">20 juin 2025</td>
              <td class="td-mono">5 j</td>
              <td><span class="statut s-attente">en attente</span></td>
              <td><button class="btn-sm btn-cancel"><i class="bi bi-x"></i> Annuler</button></td>
            </tr>
            <tr>
              <td><span class="type-badge t-maladie">Maladie</span></td>
              <td class="td-muted">2 juin 2025</td>
              <td class="td-muted">3 juin 2025</td>
              <td class="td-mono">2 j</td>
              <td><span class="statut s-approuvee">approuvée</span></td>
              <td><span class="td-muted" style="font-size:.75rem">—</span></td>
            </tr>
            <tr>
              <td><span class="type-badge t-annuel">Annuel</span></td>
              <td class="td-muted">12 mai 2025</td>
              <td class="td-muted">16 mai 2025</td>
              <td class="td-mono">5 j</td>
              <td><span class="statut s-approuvee">approuvée</span></td>
              <td><span class="td-muted" style="font-size:.75rem">—</span></td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Projet CodeIgniter 4</div>
  </div>

</div>
<?= $this->endSection() ?>
