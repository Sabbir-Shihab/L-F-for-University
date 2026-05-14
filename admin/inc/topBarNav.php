<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="<?= base_url.'admin' ?>" class="logo d-flex align-items-center">
      <img src="<?= validate_image($_settings->info('logo')) ?>" alt="System Logo">
      <span class="d-none d-lg-block"><?= $_settings->info('short_name') ?></span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <!-- <div class="search-bar">
    <form class="search-form d-flex align-items-center" method="POST" action="#">
      <input type="text" name="query" placeholder="Search" title="Enter search keyword">
      <button type="submit" title="Search"><i class="bi bi-search"></i></button>
    </form>
  </div> -->
  <!-- End Search Bar -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item pe-3 admin-account-menu">

        <button type="button" class="nav-link nav-profile admin-name-toggle d-flex align-items-center pe-0" aria-expanded="false" aria-haspopup="true">
          <img src="<?= validate_image($_settings->userdata('avatar')) ?>" alt="Profile" class="rounded-circle">
          <span>
            <?= ucwords(trim($_settings->userdata('firstname').' '.$_settings->userdata('lastname'))) ?: $_settings->userdata('username') ?>
          </span>
          <i class="bi bi-chevron-down"></i>
        </button><!-- End Profile Image Button -->

        <ul class="admin-account-dropdown">
          <li class="dropdown-header admin-account-summary">
            <img src="<?= validate_image($_settings->userdata('avatar')) ?>" alt="Profile" class="rounded-circle">
            <div>
              <h6><?= ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?></h6>
              <span><?= $_settings->userdata('type') == 1 ? "Administrator" : "Staff" ?></span>
            </div>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item admin-account-link d-flex align-items-center" href="<?= base_url."admin/?page=user" ?>">
              <i class="bi bi-gear"></i>
              <span>
                Account Settings
                <small>Update profile and password</small>
              </span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item admin-logout-link d-flex align-items-center" href="<?= base_url.'classes/Login.php?f=logout' ?>">
              <i class="bi bi-box-arrow-right"></i>
              <span>
                Logout
                <small>End this admin session</small>
              </span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header><!-- End Header -->
<script>
  document.addEventListener('DOMContentLoaded', function(){
    var accountMenu = document.querySelector('.admin-account-menu');
    if(!accountMenu) return;
    var toggle = accountMenu.querySelector('.admin-name-toggle');
    var menu = accountMenu.querySelector('.admin-account-dropdown');
    if(!toggle || !menu) return;

    function closeMenu(){
      accountMenu.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      menu.style.top = '';
      menu.style.right = '';
      menu.style.left = '';
      menu.style.width = '';
    }

    function positionMenu(){
      var rect = toggle.getBoundingClientRect();
      var viewportWidth = window.innerWidth || document.documentElement.clientWidth;
      var menuWidth = Math.min(290, viewportWidth - 24);
      menu.style.width = menuWidth + 'px';
      menu.style.top = (rect.bottom + 8) + 'px';
      menu.style.right = Math.max(12, viewportWidth - rect.right) + 'px';
      menu.style.left = 'auto';
      if(viewportWidth < 420){
        menu.style.left = '12px';
        menu.style.right = '12px';
        menu.style.width = 'auto';
      }
    }

    toggle.addEventListener('click', function(e){
      e.preventDefault();
      e.stopPropagation();
      var willOpen = !accountMenu.classList.contains('is-open');
      if(willOpen){
        positionMenu();
        accountMenu.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
      }else{
        closeMenu();
      }
    });

    menu.addEventListener('click', function(e){
      e.stopPropagation();
    });

    document.addEventListener('click', closeMenu);
    window.addEventListener('resize', function(){
      if(accountMenu.classList.contains('is-open')) positionMenu();
    });
    window.addEventListener('scroll', function(){
      if(accountMenu.classList.contains('is-open')) positionMenu();
    }, true);
  });
</script>
