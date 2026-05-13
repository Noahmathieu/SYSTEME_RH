  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li><a href="<?= base_url('employe/dashboard') ?>" class="<?= (current_url() == base_url('employe/dashboard')) ? 'active' : '' ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= base_url('employe/create') ?>" class="<?= (current_url() == base_url('employe/create')) ? 'active' : '' ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li>
        <a href="<?= base_url('employe/conges') ?>" class="<?= (current_url() == base_url('employe/conges')) ? 'active' : '' ?>">
          <i class="bi bi-calendar3"></i> Mes demandes
          <span class="nav-badge alert">2</span>
        </a>
      </li>
      <li><a href="#page-profil-employe"><i class="bi bi-person"></i> Mon profil</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green">SR</div>
        <div>
          <div class="user-name">Soa Rakoto</div>
          <div class="user-role">Employé · IT</div>
        </div>
        <a href="<?= base_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>
