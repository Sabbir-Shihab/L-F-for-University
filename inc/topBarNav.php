<!-- ======= Header ======= -->
<?php
$is_staff_logged_in = !empty($_settings->userdata('id'));
$staff_name = trim(($_settings->userdata('firstname') ?? '').' '.($_settings->userdata('lastname') ?? ''));
if($staff_name === ''){
  $staff_name = $_settings->userdata('username') ?? 'Staff';
}
$staff_role = (int)($_settings->userdata('type') ?? 0) === 1 ? 'Administrator' : 'Staff';
?>
<header id="header" class="header fixed-top">
  <div class="uu-utility-bar">
    <div class="container-fluid px-4 d-flex align-items-center justify-content-between gap-3">
      <div class="uu-utility-contact d-flex align-items-center gap-3">
        <span><i class="bi bi-telephone me-1"></i>Hunting Number: 096 40 UTTARA (888272)</span>
      </div>
      <div class="uu-utility-links d-none d-md-flex align-items-center gap-3">
        <a href="https://erp.uttara.ac.bd/">Tuition Fee Calculator</a>
        <a href="https://erp.uttara.ac.bd/">Online Admission</a>
        <a href="https://erp.uttara.ac.bd/">Running Students</a>
        <?php if($is_staff_logged_in): ?>
        <div class="dropdown uu-signin-dropdown">
          <button class="uu-signin-link uu-profile-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= validate_image($_settings->userdata('avatar')) ?>" alt="Profile">
            <span><?= htmlspecialchars($staff_name) ?></span>
            <i class="bi bi-chevron-down"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end uu-profile-menu">
            <li class="uu-profile-summary">
              <strong><?= htmlspecialchars($staff_name) ?></strong>
              <span><?= $staff_role ?></span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item" href="<?= base_url.'admin/' ?>">
                <i class="bi bi-speedometer2"></i>
                Dashboard
              </a>
            </li>
            <li>
              <a class="dropdown-item uu-profile-logout" href="<?= base_url.'classes/Login.php?f=logout' ?>">
                <i class="bi bi-box-arrow-right"></i>
                Logout
              </a>
            </li>
          </ul>
        </div>
        <?php else: ?>
        <a href="<?= base_url.'admin/' ?>" class="uu-signin-link"><i class="bi bi-shield-lock"></i> Admin Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="uu-header-main">
    <div class="container-fluid px-4 d-flex align-items-center justify-content-between gap-3 uu-header-shell">
      <a href="<?= base_url ?>" class="logo d-flex align-items-center uu-brand">
        <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Uttara University Lost and Found">
      </a><!-- End Logo -->

      <button class="navbar-toggler uu-header-toggle d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#siteTopNav" aria-controls="siteTopNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list"></i>
      </button>

      <div class="collapse navbar-collapse uu-header-collapse w-100 d-lg-flex align-items-center justify-content-end" id="siteTopNav">
        <nav class="uu-header-nav">
          <ul class="d-flex align-items-center h-100 mb-0">
            <li class="nav-item">
              <a href="<?= base_url ?>" class="nav-link"><i class="bi bi-house-door me-2 d-lg-none"></i>Home</a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url.'?page=items' ?>" class="nav-link"><i class="bi bi-search me-2 d-lg-none"></i>Browse Items</a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url.'?page=rechived' ?>" class="nav-link"><i class="bi bi-inbox me-2 d-lg-none"></i>Received</a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url.'?page=found' ?>" class="nav-link uu-nav-cta"><i class="bi bi-plus-circle me-2 d-lg-none"></i>Post Lost/Found</a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url."?page=about" ?>" class="nav-link"><i class="bi bi-info-circle me-2 d-lg-none"></i>About</a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url.'?page=contact' ?>" class="nav-link"><i class="bi bi-envelope me-2 d-lg-none"></i>Contact Us</a>
            </li>
            <?php if($is_staff_logged_in): ?>
            <li class="nav-item d-lg-none">
              <a href="<?= base_url.'admin/' ?>" class="nav-link uu-mobile-signin"><i class="bi bi-person-badge me-2"></i><?= htmlspecialchars($staff_name) ?></a>
            </li>
            <li class="nav-item d-lg-none">
              <a href="<?= base_url.'classes/Login.php?f=logout' ?>" class="nav-link uu-mobile-signin uu-mobile-logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            </li>
            <?php else: ?>
            <li class="nav-item d-lg-none">
              <a href="<?= base_url.'admin/' ?>" class="nav-link uu-mobile-signin"><i class="bi bi-shield-lock me-2"></i>Admin Login</a>
            </li>
            <?php endif; ?>
          </ul>
        </nav><!-- End Icons Navigation -->
      </div>
    </div>
  </div>
</header><!-- End Header -->
