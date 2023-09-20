<?php 
$use_bootstrap_icons = true;
$use_recaptcha = true;
$no_footer = true;
$no_header = true;
require_once("../../inc/includes.php");

if(isset($config->display_header_sign_in) && $config->display_header_sign_in){
  $no_header = false;
}
define('META_TITLE', $seoConfig['sign_in_meta_title']);
define('META_DESCRIPTION', $seoConfig['sign_in_meta_description']);
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
                <h3 class="login-heading mb-4"><?php echo $lang['welcome_back'];?></h3>
                <?php
                if (isset($_SESSION['action']) && !empty($_SESSION['action'] && $_SESSION['action_message'] != "")) {
                  echo '<div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> ' . $_SESSION['action_message'] . '</div>';
                }
                ?>

                <!-- Login -->
                <form action="<?php echo $base_url; ?>/action" method="post" novalidate>
                  <div class="form-floating mb-3">
                    <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php echo isset($_SESSION['form_email']) ? $_SESSION['form_email'] : ''; ?>" required>
                    <label for="floatingInput"><?php echo $lang['my_account_email']; ?></label>
                  </div>
                  <div class="form-floating position-relative">
                    <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="<?php echo $lang['my_account_password']; ?>" value="<?php echo isset($_SESSION['form_password']) ? $_SESSION['form_password'] : ''; ?>" required>
                    <label for="floatingPassword"><?php echo $lang['my_account_password']; ?></label>
                    <i class="bi bi-eye-slash toggle-password"></i>
                  </div>
                  <a class="text-decoration-none small" href="<?php echo $base_url; ?>/reset-password"><?php echo $lang['i_forgot_password']; ?></a>

                  <div class="d-grid mt-2">
                    <?php if($config->use_recaptcha){?>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                    <?php } ?>
                    <input type="hidden" name="action" value="login-account">
                    <button id="submit-button" class="btn btn-lg btn-primary fw-bold mb-2" type="submit"><?php echo $lang['login_button']; ?></button>
                  </div>
                </form>
                <div class="d-grid mt-3 text-center"><a class="text-decoration-none" href="<?php echo $base_url; ?>/sign-up"><?php echo $lang['dont_account_sign_up']; ?></a></div>
                <br>
                <div class="d-grid mt-3 text-center"><a class="text-decoration-none small" href="<?php echo $base_url; ?>/"><?php echo $lang['back_to_homepage']; ?></a></div>                      
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