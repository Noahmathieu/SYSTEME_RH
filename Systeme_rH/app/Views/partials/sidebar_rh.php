  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-person-check"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li><a href="<?= base_url('rh/dashboard') ?>" class="<?= (current_url() == base_url('rh/dashboard')) ? 'active' : '' ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li>
        <a href="<?= base_url('rh/demandes') ?>" class="<?= (current_url() == base_url('rh/demandes')) ? 'active' : '' ?>">
          <i class="bi bi-inbox"></i> Demandes à traiter
          <span class="nav-badge alert">4</span>
        </a>
      </li>
      <li><a href="<?= base_url('rh/historique') ?>"><i class="bi bi-archive"></i> Historique</a></li>
      <li><a href="<?= base_url('rh/soldes') ?>"><i class="bi bi-people"></i> Soldes employés</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-blue">MR</div>
        <div><div class="user-name">Marie Rabe</div><div class="user-role">Responsable RH</div></div>
        <a href="<?= base_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>
