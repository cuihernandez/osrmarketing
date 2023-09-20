<?php 
$use_recaptcha = true;
$no_footer = true;
$no_header = true;
require_once("../../inc/includes.php");
define('META_TITLE', $lang['pr_reset_password_title']);
define('META_DESCRIPTION', $lang['pr_reset_password_title']);
require_once("../../inc/header.php");
if (isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {
    header("Location: /panel");
    exit;
}
?>
  
  <div class="container-fluid ps-md-0">
    <div class="row g-0">
      <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image" style="background: url(<?php echo $base_url."/public_uploads/".$getTheme->image_fpassword; ?>);"></div>
      <div class="col-md-8 col-lg-6">
        <div class="login d-flex align-items-center py-5">
          <div class="container">
            <div class="row">
              <div class="col-md-9 col-lg-8 mx-auto">

        
                  <?php if(isset($_GET['action']) && $_GET['action'] == "enter_code"){?>

                  <?php
                  if (isset($_SESSION['action']) && !empty($_SESSION['action']) && !empty($_SESSION['action_message'])) {
                    echo '<div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> ' . $_SESSION['action_message'] . '</div>';
                  }
                  ?>

                  <h3 class="login-heading"><?php echo $lang['pr_enter_the_code']; ?></h3>
                  <p><?php echo $lang['pr_email_message']; ?></p>
          
                  <form action="<?php echo $base_url; ?>/action" method="post" novalidate>
                    <div class="form-floating mb-3">
                      <input name="recovery_password_token" type="number" class="form-control" id="floatingInput" required>
                      <label for="floatingInput"><?php echo $lang['pr_input_code']; ?></label>
                    </div>


                    <div class="d-grid mt-2">       
                      <?php if($config->use_recaptcha){?>
                      <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                      <?php } ?>                                   
                      <input type="hidden" name="action" value="check-password-code">
                      <button class="btn btn-lg btn-primary fw-bold mb-2" type="submit"><?php echo $lang['pr_continue_button']; ?></button>
                      <div class="d-grid mt-3 text-center"><a class="text-decoration-none small" href="<?php echo $base_url; ?>/reset-password"><?php echo $lang['pr_send_new_code']; ?></a></div>   
                    </div>
                  </form>

                  <?php }else{ ?>

                  <?php
                  if (isset($_SESSION['action']) && !empty($_SESSION['action'])) {
                    echo '<div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> ' . $_SESSION['action_message'] . '</div>';
                  }
                  ?>

                  <h3 class="login-heading"><?php echo $lang['pr_reset_password_title']; ?></h3>
                  <p><?php echo $lang['pr_reset_password_instructions']; ?></p>                    
                  <form action="<?php echo $base_url; ?>/action" method="post" novalidate>
                    <div class="form-floating mb-3">             
                      <input name="email" type="email" class="form-control" id="floatingInput" required>
                      <label for="floatingInput"><?php echo $lang['my_account_email']; ?></label>
                    </div>

                    <div class="d-grid mt-2">
                      <?php if($config->use_recaptcha){?>
                      <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                      <?php } ?>                                   
                      <input type="hidden" name="action" value="reset-password">
                      <button id="submit-button" class="btn btn-lg btn-primary fw-bold mb-2" type="submit"><?php echo $lang['pr_send_recovery_code']; ?></button>
                    </div>
                  </form>
                  <?php } ?>
                <div class="d-grid mt-3 text-center"><a class="text-decoration-none small" href="<?php echo $base_url; ?>/sign-in"><?php echo $lang['back_to_login']; ?></a></div>                      
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