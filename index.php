<?php require_once('./config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php') ?>
     <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home';  ?>
  <body class="toggle-sidebar <?= $page == 'home' ? 'home-page' : '' ?>">
    <style>
      #banner-slider{
        height: 400px;
      }
      #banner-slider .carousel-inner {
          height: 100%;
      }
      #banner-slider img.d-block.w-100 {
          object-fit: cover;
          object-position: center center;
      }
    </style>
     <?php 
      $pageSplit = explode("/",$page);  
      if(isset($pageSplit[1]))
      $pageSplit[1] = (strtolower($pageSplit[1]) == 'list') ? $pageSplit[0].' List' : $pageSplit[1];
     ?>
     
     <?php require_once('inc/topBarNav.php') ?>
      <!-- Content Wrapper. Contains page content -->
      <main id="main" class="main">
        <?php if(in_array($page, ['home'])): ?>
          <div class="col-12">
            <?php
              $banner_dir = base_app.'uploads/banner/';
              $banner_url = base_url.'uploads/banner/';
              $banner_slides = [[
                'src' => validate_image($_settings->info('cover')),
                'mtime' => ''
              ]];
              if(is_dir($banner_dir)){
                foreach(scandir($banner_dir) as $banner_file){
                  if(in_array($banner_file, ['.', '..']))
                    continue;
                  if(preg_match('/\.(jpe?g|png|webp)$/i', $banner_file))
                    $banner_slides[] = [
                      'src' => $banner_url.rawurlencode($banner_file),
                      'mtime' => filemtime($banner_dir.$banner_file)
                    ];
                }
              }
            ?>
            <div id="site-header" class="carousel slide site-banner-carousel" data-bs-ride="carousel">
              <div class="carousel-inner">
                <?php foreach($banner_slides as $i => $slide): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                  <img src="<?= $slide['src'].($slide['mtime'] ? '?v='.$slide['mtime'] : '') ?>" class="d-block w-100" alt="Campus banner <?= $i + 1 ?>">
                </div>
                <?php endforeach; ?>
              </div>
              <?php if(count($banner_slides) > 1): ?>
              <button class="carousel-control-prev" type="button" data-bs-target="#site-header" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#site-header" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
              <?php endif; ?>
              <div class="header-content">
                <div class="banner-copy">
                  <span class="banner-eyebrow"><i class="bi bi-shield-check"></i> Uttara University Campus Service</span>
                  <h1 class="siteTitle">Lost &amp; Found</h1>
                  <p class="banner-lead">Report, browse, and recover campus belongings through one trusted university platform.</p>
                  <div class="banner-actions">
                    <a href="<?= base_url.'?page=items' ?>" class="btn btn-primary btn-lg">
                      <i class="bi bi-search"></i>
                      Browse Items
                    </a>
                    <a href="<?= base_url.'?page=found' ?>" class="btn btn-light btn-lg">
                      <i class="bi bi-plus-circle"></i>
                      Post Item
                    </a>
                  </div>
                  <div class="banner-highlights" aria-label="Service highlights">
                    <span><i class="bi bi-check-circle"></i> Verified requests</span>
                    <span><i class="bi bi-clock-history"></i> Quick updates</span>
                    <span><i class="bi bi-geo-alt"></i> Campus focused</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div class="container-xl px-4">
          <div id="msg-container">
          <?php if($_settings->chk_flashdata('success')): ?>
          <script>
            alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
          </script>
          <?php endif;?>   
          </div>
          <?php 
            if(!file_exists($page.".php") && !is_dir($page)){
                include '404.html';
            }else{
              if(is_dir($page))
                include $page.'/index.php';
              else
                include $page.'.php';

            }
          ?>
        </div>
      </main>
  
      <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
          <div class="modal-content rounded-0">
            <div class="modal-header">
            <h5 class="modal-title"></h5>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary bg-gradient-teal border-0 rounded-0" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
            <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Cancel</button>
          </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="uni_modal_right" role='dialog'>
        <div class="modal-dialog modal-full-height  modal-md rounded-0" role="document">
          <div class="modal-content rounded-0">
            <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span class="fa fa-arrow-right"></span>
            </button>
          </div>
          <div class="modal-body">
          </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
          <div class="modal-content rounded-0">
            <div class="modal-header">
            <h5 class="modal-title">Confirmation</h5>
          </div>
          <div class="modal-body">
            <div id="delete_content"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary  bg-gradient-teal border-0 rounded-0" id='confirm' onclick="">Continue</button>
            <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Close</button>
          </div>
          </div>
        </div>
      </div>
    <div class="modal fade" id="viewer_modal" role='dialog'>
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
                <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                <img src="" alt="">
        </div>
      </div>
    </div>
    <?php require_once('inc/footer.php') ?>
  </body>
</html>
