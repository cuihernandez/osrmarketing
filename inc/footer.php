    <?php 
    if (isset($_SESSION['action'])) {
        unset($_SESSION['action']);
    }
    if (isset($_SESSION['action_message'])) {
        unset($_SESSION['action_message']);
    };

    if(!isset($no_footer)){?>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <h5 class="h1 text-white"><strong><?php echo $lang['footer_title']; ?></strong></h5>
                        <p><?php echo $lang['footer_resume']; ?></p>
                    </div>
                    <div class="col-lg-3 offset-lg-1 col-md-6">
                        <h5><?php echo $lang['footer_title_col1']; ?></h5>
                        <ul class="list-unstyled text-muted">
                            <?php 
                            $getMenu = $menus->getListPosition("Footer Col1");
                            foreach ($getMenu as $showMenu) {?>
                                <li>
                                    <a href="<?php echo $showMenu->slug; ?>" <?php echo ($showMenu->target_blank == 1) ? 'target="_blank"' : ''; ?>>
                                        <?php echo $showMenu->name; ?>
                                    </a>
                                </li>
                            <?php } ?>                        
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <h5><?php echo $lang['footer_title_col2']; ?></h5>
                        <ul class="list-unstyled text-muted">
                            <?php 
                            $getMenu = $menus->getListPosition("Footer Col2");
                            foreach ($getMenu as $showMenu) {?>
                                <li>
                                    <a href="<?php echo $showMenu->slug; ?>" <?php echo ($showMenu->target_blank == 1) ? 'target="_blank"' : ''; ?>>
                                        <?php echo $showMenu->name; ?>
                                    </a>
                                </li>
                            <?php } ?>                        
                        </ul>
                    </div>                
                    <div class="col-lg-1 col-md-6 text-end">
                        <img onclick="backToTop();" src="<?php echo $base_url; ?>/img/icon-top.svg" alt="<?php echo $lang['back_to_top']; ?>" title="<?php echo $lang['back_to_top']; ?>">
                    </div>
                </div>
            </div>
        </footer>
    <?php } ?>


    <!-- JavaScript Libraries -->
    <script src="<?php echo $base_url; ?>/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo $base_url; ?>/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo $base_url; ?>/js/toastr.min.js"></script>
    <?php if(isset($loadAI) && $loadAI){?>
    <script src="<?php echo $base_url; ?>/js/highlight.min.js"></script>
    <script src="<?php echo $base_url; ?>/js/sse.js"></script>
    <script src="<?php echo $base_url; ?>/js/vfs_fonts.js"></script>
    <script src="<?php echo $base_url; ?>/js/purify.min.js"></script>
    <?php } ?>
    <?php if(isset($blog) && $blog){?>
    <script src="<?php echo $base_url; ?>/js/sharer.min.js"></script>
    <?php } ?>

    <!-- Main script -->
    <script src="<?php echo $base_url; ?>/js/main.js?v<?php echo $config->js_version; ?>"></script>
    <?php 
    require_once("footer-scripts.php");
    ?>


  <?php if ($config->display_cookies_alert){ ?>
  <div class="alert-dismissible fade show fixed-bottom container" id="cookie-banner" role="alert" style="display:none">
    <p class="mb-2"><?php echo $lang['cookie_message'];?></p>
    <div>
      <button type="button" class="btn btn-secondary btn-sm me-2" id="deny-button"><?php echo $lang['cookie_decline_btn'];?></button>
      <button type="button" class="btn btn-primary btn-sm" id="accept-button"><?php echo $lang['cookie_accept_btn'];?></button>
    </div>
  </div>
  <?php } ?>



</body>
</html>