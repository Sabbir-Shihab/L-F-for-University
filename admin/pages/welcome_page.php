<?php 
if(is_file(base_app.'pages/welcome.html')){
  $content = file_get_contents(base_app.'pages/welcome.html');
}
$slider_path = base_app.'uploads/welcome_slider/';
$slider_images = [];
if(is_dir($slider_path)){
  foreach(scandir($slider_path) as $file){
    if(preg_match('/\.(jpe?g|png|webp)$/i', $file) && is_file($slider_path.$file)){
      $slider_images[] = $file;
    }
  }
  sort($slider_images, SORT_NATURAL);
}
?>
<div class="card rounded-0">
  <div class="card-body rounded-0 pt-4">
    <div class="container-fluid">
      <form action="" id="page-form">
        <textarea name="page[welcome]" id="" cols="30" rows="10" class="form-control tinymce-editor" requried=""><?= $content ?? "" ?></textarea>
        <div class="mt-3">
          <label class="form-label fw-bold">Welcome Section Slider Images</label>
          <div class="welcome-slider-upload-grid">
            <?php for($i = 1; $i <= 5; $i++): ?>
            <label class="welcome-slider-upload-slot" for="welcomePageSliderImage<?= $i ?>">
              <span>Slide <?= $i ?></span>
              <input type="file" class="form-control" id="welcomePageSliderImage<?= $i ?>" name="welcome_slider[]" accept=".png,.jpg,.jpeg,.webp">
            </label>
            <?php endfor; ?>
          </div>
          <small class="text-muted d-block mt-2">Upload 1 to 5 images. New uploads replace the current welcome slider images. If no image is uploaded, existing images remain unchanged.</small>
        </div>
        <?php if(!empty($slider_images)): ?>
        <div class="welcome-slider-admin-preview mt-3">
          <?php foreach($slider_images as $file): ?>
          <div class="welcome-slider-admin-item img-item">
            <img src="<?= base_url.'uploads/welcome_slider/'.rawurlencode($file).'?v='.filemtime($slider_path.$file) ?>" alt="Welcome slider image">
            <button class="btn btn-sm btn-danger rem_img" type="button" data-path="<?= $slider_path.$file ?>"><i class="bi bi-trash"></i></button>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </form>
    </div>
  </div>
  <div class="card-footer">
    <div class="col-lg-4 col-md-5 col-sm-10 col-12 mx-auto">
      <button class="btn btn-block w-100 btn-primary" form="page-form">Update</button>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    window.delete_img = function($path){
      start_loader()
      $.ajax({
        url: _base_url_+'classes/Master.php?f=delete_img',
        data:{path:$path},
        method:'POST',
        dataType:"json",
        error:err=>{
          console.log(err)
          alert_toast("An error occured while deleting an Image","error");
          end_loader()
        },
        success:function(resp){
          $('.modal').modal('hide')
          if(typeof resp =='object' && resp.status == 'success'){
            $('[data-path="'+$path+'"]').closest('.img-item').hide('slow',function(){
              $('[data-path="'+$path+'"]').closest('.img-item').remove()
            })
            alert_toast("Image Successfully Deleted","success");
          }else{
            console.log(resp)
            alert_toast("An error occured while deleting an Image","error");
          }
          end_loader()
        }
      })
    }
    $('.rem_img').click(function(){
      _conf("Are sure to delete this image permanently?", 'delete_img', [$(this).attr('data-path')])
    })
    $('#page-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			setTimeout(() => {
				start_loader();
				$.ajax({
					url:_base_url_+"classes/Master.php?f=save_page",
					data: new FormData($(this)[0]),
					cache: false,
					contentType: false,
					processData: false,
					method: 'POST',
					type: 'POST',
					dataType: 'json',
					error:err=>{
						console.log(err)
						alert_toast("An error occured",'error');
						end_loader();
					},
					success:function(resp){
						if(typeof resp =='object' && resp.status == 'success'){
							location.reload()
						}else if(resp.status == 'failed' && !!resp.msg){
							var el = $('<div>')
								el.addClass("alert alert-danger err-msg").text(resp.msg)
								_this.prepend(el)
								el.show('slow')
								$("html, body").scrollTop(0);
								end_loader()
						}else{
							alert_toast("An error occured",'danger');
							end_loader();
						}
					}
				})
			}, 200);
			
		})
  })
</script>
