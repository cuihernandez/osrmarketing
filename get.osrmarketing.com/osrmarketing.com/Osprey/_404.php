<?php 
$header_min = true;
$use_bootstrap_icons = true;
require_once("inc/includes.php");
require_once("inc/header.php");
?>


  <section>
    <div class="container section-spacing">
      <div class="row text-center mt-4">
        <div class="col-12">
          <img src="<?php echo $base_url; ?>/img/404.svg" alt="404 - Page not found" class="mb-3">
          <div class="alert alert-light"><i class="bi bi-exclamation-octagon"></i> <?php echo $lang['message_404']; ?></div>        
        </div>
      </div>
    </div>
  </section>

<?php
require_once("inc/footer.php");
?>