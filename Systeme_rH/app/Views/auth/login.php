<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="auth-page geo-bg">
<div class="auth-split">
  <!-- Panneau gauche -->
  <div class="auth-left">
    <div>
      <p class="auth-left-brand">TechMada RH<span>Gestion des congés</span></p>
      <p class="auth-left-text" style="margin-top:2rem">
        <strong>Bienvenue sur votre espace RH.</strong>
        Gérez vos demandes de congés, consultez votre solde et suivez l'état de vos demandes en temps réel.
      </p>
    </div>
    <div class="auth-roles">
      <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.25);margin-bottom:4px">Comptes de démonstration</div>
      <div class="role-pill">
        <i class="bi bi-shield-check"></i>
        <div><div class="role-pill-name">Administrateur</div><div class="role-pill-cred">admin@local.com · admin</div></div>
      </div>
      <div class="role-pill">
        <i class="bi bi-person-check"></i>
        <div><div class="role-pill-name">Responsable RH</div><div class="role-pill-cred">rh@techmada.mg · rh123</div></div>
      </div>
      <div class="role-pill">
        <i class="bi bi-person"></i>
        <div><div class="role-pill-name">Employé</div><div class="role-pill-cred">employe@techmada.mg · emp123</div></div>
      </div>
    </div>
  </div>

  <!-- Panneau droit -->
  <div class="auth-right">
    <p class="auth-title">Connexion</p>
    <p class="auth-sub">Entrez vos identifiants pour accéder à votre espace.</p>

    <!-- Flashdata CI4 — erreur -->
    <!--
    <div class="flash flash-error">
      <i class="bi bi-exclamation-circle-fill"></i>
      Identifiants incorrects. Veuillez réessayer.
    </div>
    -->

    <form method="post" action="<?= base_url('login') ?>">
      <div class="f-group">
        <label class="f-label">Adresse email</label>
        <input type="email" name="email" class="f-input" placeholder="vous@techmada.mg" value="employe@techmada.mg"/>
      </div>
      <div class="f-group">
        <label class="f-label">Mot de passe</label>
        <div style="position:relative">
          <input type="password" id="password" name="password" class="f-input" placeholder="votre Mot de passe" value="emp123" style="padding-right:42px"/>
          <button type="button" id="togglePassword" aria-label="Afficher ou masquer le mot de passe" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);border:none;background:transparent;color:var(--muted);padding:4px;display:flex;align-items:center;justify-content:center;cursor:pointer">
            <i id="passwordEyeIcon" class="bi bi-eye"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="btn-primary" style="margin-top:.5rem">
        Se connecter <i class="bi bi-arrow-right-short"></i>
      </button>
    </form>
  </div>
</div>
</div>
<?= $this->endSection() ?>
<script>
  let passwordInput = document.getElementById('password');
  const togglePasswordBtn = document.getElementById('togglePassword');
  const passwordEyeIcon = document.getElementById('passwordEyeIcon');

  if (togglePasswordBtn && passwordInput && passwordEyeIcon) {
    togglePasswordBtn.addEventListener('click', function(e) {
      e.preventDefault();
      const isHidden = (passwordInput.getAttribute('type') || '') === 'password';
      try {
        passwordInput.type = isHidden ? 'text' : 'password';
      } catch (err) {
        passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
      }
      passwordEyeIcon.className = 'bi ' + (isHidden ? 'bi-eye-slash' : 'bi-eye');
    });
  }

  if (passwordInput) {
    passwordInput.addEventListener('keyup', function(e) {
      if (e.key === 'Enter') {
        this.form.submit();
      }
    });
  }
</script>
