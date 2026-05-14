<script>
  $(document).ready(function(){
     window.viewer_modal = function($src = ''){
      start_loader()
      var t = $src.split('.')
      t = t[1]
      if(t =='mp4'){
        var view = $("<video src='"+$src+"' controls autoplay></video>")
      }else{
        var view = $("<img src='"+$src+"' />")
      }
      $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
      $('#viewer_modal .modal-content').append(view)
      $('#viewer_modal').modal({
              show:true,
              backdrop:'static',
              keyboard:false,
              focus:true
            })
            end_loader()  

  }
    window.uni_modal = function($title = '' , $url='',$size=""){
        start_loader()
        $.ajax({
            url:$url,
            error:err=>{
                console.log()
                alert("An error occured")
            },
            success:function(resp){
                if(resp){
                    $('#uni_modal .modal-title').html($title)
                    $('#uni_modal .modal-body').html(resp)
                    if($size != ''){
                        $('#uni_modal .modal-dialog').addClass($size+'  modal-dialog-centered')
                    }else{
                        $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md modal-dialog-centered")
                    }
                    $('#uni_modal').modal({
                      show:true,
                      backdrop:'static',
                      keyboard:false,
                      focus:true
                    })
                    end_loader()
                }
            }
        })
    }
    window._conf = function($msg='',$func='',$params = []){
       $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
       $('#confirm_modal .modal-body').html($msg)
       $('#confirm_modal').modal('show')
    }
  })
</script>
<!-- ======= Footer ======= -->
<footer id="footer" class="footer uu-footer">
    <div class="uu-footer-main">
      <div class="container-xl px-4">
        <div class="row gy-4">
          <div class="col-lg-3 col-md-12 uu-footer-connect">
            <h5>Connect With Us</h5>
            <div class="uu-footer-social d-flex align-items-center gap-2">
              <a href="https://www.facebook.com/uttarauniversity" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
              <a href="https://www.youtube.com/user/UttaraUniversity" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
              <a href="https://www.linkedin.com/school/uttara-university/" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
              <a href="https://www.uttara.ac.bd/" aria-label="Website"><i class="bi bi-globe2"></i></a>
              <a href="mailto:info@uttara.ac.bd" aria-label="Email"><i class="bi bi-envelope"></i></a>
              <a href="tel:09640888272" aria-label="Phone"><i class="bi bi-telephone"></i></a>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 uu-footer-link-group">
            <h5>Admission</h5>
            <ul>
              <li><a href="https://www.uttara.ac.bd/admission/why-study-at-uu/">Why Study at UU</a></li>
              <li><a href="https://www.uttara.ac.bd/admission/entry-requirements/">Entry Requirements</a></li>
              <li><a href="https://www.uttara.ac.bd/admission/payment-guidelines/">Payment Guidelines</a></li>
              <li><a href="https://www.uttara.ac.bd/admission/tuition-fees/">Tuition &amp; Fees</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-6 uu-footer-link-group">
            <h5>About</h5>
            <ul>
              <li><a href="https://www.uttara.ac.bd/about-uu/">About UU</a></li>
              <li><a href="https://www.uttara.ac.bd/about-uu/overview-features-facilities/">Overview</a></li>
              <li><a href="https://www.uttara.ac.bd/about-uu/mission-vision-motto/">Mission, Vision &amp; Motto</a></li>
              <li><a href="https://www.uttara.ac.bd/about-uu/our-unique-features/">Our Unique Features</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-6 uu-footer-link-group">
            <h5>Core Links</h5>
            <ul>
              <li><a href="https://erp.uttara.ac.bd/">Running Students</a></li>
              <li><a href="https://erp.uttara.ac.bd/result_archive">Result Archive</a></li>
              <li><a href="https://library.uttara.ac.bd/">Library</a></li>
              <li><a href="https://erp.uttara.ac.bd/">MYUU ERP</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-6 uu-footer-link-group">
            <h5>Quick links</h5>
            <ul>
              <li><a href="<?= base_url.'?page=contact' ?>">Contact Us</a></li>
              <li><a href="https://www.uttara.ac.bd/news-events/">News &amp; Events</a></li>
              <li><a href="https://www.uttara.ac.bd/notices/">Notice &amp; Announcements</a></li>
              <li><a href="https://www.uttara.ac.bd/privacy-policy/">Privacy Policy</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="uu-footer-bottom">
      <div class="container-xl px-4 text-center">
        <span>© <?= date('Y') ?> Uttara University | Dhaka, Bangladesh. All Rights Reserved.</span>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
   
<!-- Vendor JS Files -->
<script src="<?= base_url ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?= base_url ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url ?>assets/vendor/chart.js/chart.umd.js"></script>
<script src="<?= base_url ?>assets/vendor/echarts/echarts.min.js"></script>
<script src="<?= base_url ?>assets/vendor/quill/quill.min.js"></script>
<script src="<?= base_url ?>assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="<?= base_url ?>assets/vendor/tinymce/tinymce.min.js"></script>
<script src="<?= base_url ?>assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="<?= base_url ?>assets/js/main.js"></script>
