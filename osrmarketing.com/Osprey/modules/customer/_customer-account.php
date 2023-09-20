<?php 
$header_min = true;
$no_footer = true;
$use_bootstrap_icons = true;
require_once("customer-restrict.php");
define('META_TITLE', $lang['my_account_title']);
define('META_DESCRIPTION', $lang['my_account_title']);
require_once("../../inc/includes.php");
require_once("../../inc/header.php");
?>

<section id="inner-page">
  <div class="container">
    <div class="row">
      <div class="col"><h3><?php echo $lang['my_account_title']; ?></h3></div>
    </div>
  </div>  
</section>

<section id="panel-area">
  <div class="container">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-3 col-lg-3">
        <?php require_once("_customer-sidebar.php");?>      
      </div>
      <div class="col">
        <div class="white-card content-panel">
          <h4 class="mb-3"><?php echo $lang['my_account_title']; ?></h4>

                <?php
                    if (!empty($_SESSION['action'])) {
                        $alertClass = $_SESSION['action'] === 'error' ? 'danger' : $_SESSION['action'];
                        echo "<div class='alert alert-$alertClass'>{$_SESSION['action_message']}</div>";
                    }
                ?>
                <!-- Login -->
                <form action="<?php echo $base_url; ?>/action" method="post" novalidate>
                  <div class="form-floating mb-3">
                    <input name="name" type="text" class="form-control" id="floatingInputName" placeholder="<?php echo $lang['my_account_name_input']; ?>" value="<?php echo isset($getCustomer->name) ? $getCustomer->name : ''; ?>" required>
                    <label for="floatingInputName"><?php echo $lang['my_account_name_input']; ?></label>
                  </div>                  
                  <div class="form-floating mb-3">
                    <input disabled type="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php echo isset($_SESSION['email_customer']) ? $_SESSION['email_customer'] : ''; ?>" required>
                    <label for="floatingInput"><?php echo $lang['my_account_email']; ?></label>
                  </div>
                  <div class="form-floating mb-0 position-relative">
                    <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="password" minlength="6">
                    <label for="floatingPassword"><?php echo $lang['my_account_password']; ?></label>
                    <i class="bi bi-eye-slash toggle-password"></i>
                  </div>
                  <div class="progress password-progress">
                    <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <span id="password-strength-text" class="form-text"></span>     
                  <br>             

                  <div class="d-grid">
                    <input type="hidden" name="action" value="update-account">
                    <button class="btn btn-md btn-primary fw-bold mb-2" type="submit"><?php echo $lang['my_account_btn_save']; ?></button>
                  </div>

                </form>

        </div>
      </div>
    </div>
  </div>
</section>



<?php 
require_once("../../inc/footer.php");
?>