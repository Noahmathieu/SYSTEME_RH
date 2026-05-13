  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)"><i class="bi bi-shield-check" style="color:var(--leaf)"></i></div>
      <div class="sidebar-brand-name">TechMada RH
        <span>Administration</span>
      </div>
    </div>
    <div class="sidebar-section">Gestion</div>
    <ul class="sidebar-nav">
      <li><a href="<?= base_url('admin/dashboard') ?>" class="<?= (current_url() == base_url('admin/dashboard')) ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li>
        <a href="<?= base_url('rh/demandes') ?>">
          <i class="bi bi-inbox"></i> Toutes les demandes
          <span class="nav-badge alert">4</span>
        </a>
      </li>
      <li><a href="<?= base_url('admin/employes') ?>" class="<?= (current_url() == base_url('admin/employes')) ? 'active' : '' ?>"><i class="bi bi-people"></i> Employés</a></li>
      <li><a href="<?= base_url('admin/departements') ?>" class="<?= (current_url() == base_url('admin/departements')) ? 'active' : '' ?>"><i class="bi bi-building"></i> Départements</a></li>
      <li><a href="<?= base_url('admin/types_conges') ?>" class="<?= (current_url() == base_url('admin/types_conges')) ? 'active' : '' ?>"><i class="bi bi-tags"></i> Types de congé</a></li>
      <li><a href="<?= base_url('admin/soldes') ?>"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar" style="background:#5a2d82;width:32px;height:32px;font-size:.7rem">AD</div>
        <div><div class="user-name">Administrateur</div><div class="user-role">Admin système</div></div>
        <a href="<?= base_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>
