<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
<body>
  <style>
    body{
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size:cover;
      background-repeat:no-repeat;
      backdrop-filter: brightness(.7);
      overflow-x:hidden;
    }
    body:before {
      content: "";
      position: fixed;
      inset: 0;
      background: linear-gradient(120deg, rgba(3, 20, 49, .88), rgba(3, 20, 49, .6));
      pointer-events: none;
    }
    main {
      position: relative;
      z-index: 1;
    }
    /* #page-title{
      text-shadow: 6px 4px 7px black;
      font-size: 3.5em;
      color: #fff4f4 !important;
      background: #8080801c;
    } */
    .logo img {
        max-height: 55px;
        margin-right: 25px;
    }
    .logo span{
      color: #fff;
      text-shadow:0px 0px 10px #000;
    }
    .admin-login-card {
      border: 1px solid rgba(255, 255, 255, .18);
      border-radius: 8px;
      box-shadow: 0 24px 60px rgba(0, 0, 0, .28);
    }
    .admin-login-badge {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      padding: .38rem .6rem;
      border-radius: 999px;
      background: #f6f9ff;
      color: #06479a;
      font-size: .72rem;
      font-weight: 800;
      letter-spacing: .06em;
      text-transform: uppercase;
    }
    .admin-security-note {
      border: 1px solid rgba(6, 71, 154, .12);
      border-left: 4px solid #f1b51c;
      border-radius: 6px;
      background: #f9fbff;
      color: #344054;
      font-size: .84rem;
      line-height: 1.5;
    }
  </style>
  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="<?= base_url ?>" class="logo d-flex align-items-center w-auto">
                  <img src="<?= validate_image($_settings->info('logo')) ?>" alt="">
                  <span class="d-none d-lg-block text-center"><?= $_settings->info('name') ?></span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3 admin-login-card">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <div class="text-center mb-3">
                      <span class="admin-login-badge"><i class="bi bi-shield-lock"></i> Staff Access</span>
                    </div>
                    <h5 class="card-title text-center pb-0 fs-4">Admin Login</h5>
                    <p class="text-center small mb-0">Authorized Uttara University staff only</p>
                  </div>

                  <form class="row g-3 needs-validation" novalidate id="login-frm">

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="username" class="form-control" id="yourUsername" autocomplete="username" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" autocomplete="current-password" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <!-- <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                    </div> -->
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                    <div class="col-12">
                      <div class="admin-security-note p-3">
                        Keep admin credentials confidential. Do not share passwords, save them on public devices, or use this panel from an untrusted computer.
                      </div>
                    </div>
                    <!-- <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="pages-register.html">Create an account</a></p>
                    </div> -->
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- jQuery -->
<script src="<?= base_url ?>assets/js/jquery-3.6.4.min.js"></script>
<script src="<?= base_url ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?= base_url ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url ?>assets/vendor/chart.js/chart.umd.js"></script>
<script src="<?= base_url ?>assets/vendor/echarts/echarts.min.js"></script>
<script src="<?= base_url ?>assets/vendor/quill/quill.min.js"></script>
<script src="<?= base_url ?>assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="<?= base_url ?>assets/vendor/tinymce/tinymce.min.js"></script>
<script src="<?= base_url ?>assets/vendor/php-email-form/validate.js"></script>
<script src="<?= base_url ?>assets/js/main.js"></script>

<script>
  $(document).ready(function(){
    end_loader();
  })
</script>
</body>
</html>
