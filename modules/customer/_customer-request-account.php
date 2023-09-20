<?php 
$no_footer = true;
$no_header = true;
require_once("../../inc/includes.php");
define('META_TITLE', $lang['request_account_continue']);
define('META_DESCRIPTION', $lang['request_account_continue']);
require_once("../../inc/header.php");
if (isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {
    header("Location: /panel");
    exit;
}
?>
  
  <div class="container-fluid ps-md-0">
    <div class="row g-0">
      <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image" style="background: url(<?php echo $base_url."/public_uploads/".$getTheme->image_sign_in; ?>);"></div>
      <div class="col-md-8 col-lg-6">
        <div class="login d-flex align-items-center py-5">
          <div class="container">
            <div class="row">
              <div class="col-md-9 col-lg-8 mx-auto">

                <h3 class="login-heading mb-4 text-center"><?php echo $lang['request_account_continue']; ?></h3>

                <div class="row">
                  <div class="col-md-6">
                    <div class="card mb-4 rounded-3 shadow-sm">
                      <div class="card-body">
                        <div class="card-price-thumb mb-2"><img src="<?php echo $base_url;?>/img/sign-in.png" alt="<?php echo $lang['sign_in']; ?>" title="<?php echo $lang['sign_in']; ?>"></div>
                        <a href="<?php echo $base_url."/sign-in"; ?>" class="w-100 btn btn-lg btn-outline-primary"><?php echo $lang['sign_in']; ?></a>
                      </div>
                    </div>                    
                  </div>

                  <div class="col-md-6">
                    <div class="card mb-4 rounded-3 shadow-sm">
                      <div class="card-body">
                        <div class="card-price-thumb mb-2"><img src="<?php echo $base_url;?>/img/sign-up.png" alt="<?php echo $lang['sign_up']; ?>" title="<?php echo $lang['sign_up']; ?>"></div>
                        <a href="<?php echo $base_url."/sign-up"; ?>" class="w-100 btn btn-lg btn-outline-primary"><?php echo $lang['sign_up']; ?></a>
                      </div>
                    </div>                    
                  </div>

                </div>                

                <div class="d-grid mt-3 text-center"><a class="text-decoration-none small" href="<?php echo $base_url; ?>/"><?php echo $lang['back_to_homepage']; ?></a></div>
                <div class="d-grid mt-3 text-center"><a class="text-decoration-none small" href="<?php echo $base_url; ?>/pricing"><?php echo $lang['back_to_pricing']; ?></a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


<?php 
require_once("../../inc/footer.php");
?>