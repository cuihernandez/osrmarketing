<!doctype html>
<html lang="<?php echo $getDefaultLanguage->lang; ?>" dir="<?php echo $dir; ?>">
<head>
  <meta charset="<?php echo $config->meta_charset; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo defined('META_TITLE') ? META_TITLE : 'Aigency'; ?></title>
  <meta name="description" content="<?php echo defined('META_DESCRIPTION') ? META_DESCRIPTION : 'Aigency'; ?>">
  <?php if (!is_null($getSeo->image_thumb)) : ?>
    <meta property="og:image" content="<?php echo defined('OG_IMAGE') ? OG_IMAGE : $base_url."/public_uploads/".$getSeo->image_thumb; ?>" />
  <?php endif; ?>
  <meta name="theme-color" content="<?php echo $seoConfig['theme_color']; ?>">
  <meta property="og:title" content="<?php echo defined('META_TITLE') ? META_TITLE : 'Aigency'; ?>" />
  <meta property="og:description" content="<?php echo defined('META_DESCRIPTION') ? META_DESCRIPTION : 'Aigency'; ?>" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet">
  <link href="<?php echo $bootstrapCSS; ?>" rel="stylesheet">
  <link href="<?php echo $base_url; ?>/style/app.css?v<?php echo $config->css_version; ?>" rel="stylesheet">
  <link href="<?php echo $base_url; ?>/style/dark-mode.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $base_url; ?>/style/highlight.min.css" />
  <link rel="stylesheet" href="<?php echo $base_url; ?>/style/highlight.dark.min.css" />
  <link rel="stylesheet" href="<?php echo $base_url; ?>/style/toastr.min.css" />
  <?php if(isset($use_bootstrap_icons) && $use_bootstrap_icons){?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <?php } ?>

  <?php if(isset($maintenance_mode) && $maintenance_mode){?>
  <link rel="stylesheet" href="<?php echo $base_url; ?>/style/maintenance.css" />
  <?php } ?>

  <link itemprop="url" href="<?php echo $base_url; ?>/img/thumb.jpg">
  <link itemprop="thumbnailUrl" href="<?php echo $base_url; ?>/img/thumb.jpg">
  <link rel="icon" type="image/x-icon" href="<?php echo ($getTheme->image_fav) ? $base_url.'/public_uploads/'.$getTheme->image_fav : $base_url.'/favicon.ico'; ?>">
  <?php require_once("styles.php");?>
</head>

<body class="<?php 
  if(isset($config->force_dark_mode) && $config->force_dark_mode) echo 'dark-mode ';
  if(isset($mobile_bg) && $mobile_bg) echo 'mobile-body '; 
  if((isset($bg_white) && $bg_white) || (isset($embed_chat) && $embed_chat)) echo 'bg-white'; 
?>">


  <?php if($config->maintenance_mode && isset($_SESSION['admin_id'])){?>
    <div class="maintenance-mode"><?php echo $lang['maintenance_mode']; ?></div>
  <?php } ?>

  <?php if (!isset($no_header) || !$no_header) {?>
  <header <?php if(isset($header_min)) echo "class='header-min'";?>>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-3 col-md-3 col-6 d-flex justify-content-md-start">
          <a href="<?php echo $base_url; ?>/"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $getTheme->image_logo; ?>" alt="<?php echo $lang['company_name']; ?>" title="<?php echo $lang['company_name']; ?>" id="logo"></a>
        </div>
        <div class="col-lg-9 col-md-9 col-6 d-flex justify-content-end">

          <nav class="navbar navbar-expand-lg navbar-light nav-mobile">
            <button class="navbar-toggler ms-auto custom-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas">
              <i class="bi bi-list fs-1"></i>
            </button>
            <div class="offcanvas offcanvas-custom offcanvas-end" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
              <div class="d-lg-none ">
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas">
                  <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="offcanvas-body">
                <ul class="navbar-nav primary-menu">
                  <?php 
                  $getMenuHeader = $menus->getListPosition("Header");
                  foreach ($getMenuHeader as $showMenuHeader) {?>
                  <li class="nav-item"><a class="nav-link nav-link-effect" href="<?php echo $showMenuHeader->slug; ?>"><?php echo $showMenuHeader->name; ?></a></li>
                  <?php } ?>
                  <?php if(!$isLogged){?>
                  <li class="nav-item">
                    <a class="nav-link btn btn-sign-up" href="<?php echo $base_url; ?>/sign-up"><i class="bi bi-box-arrow-in-right fs-5"></i> <?php echo $lang['sign_up']; ?></a>
                  </li>                                
                  <li class="nav-item">
                    <a class="nav-link btn btn-sign-in" href="<?php echo $base_url; ?>/sign-in"><i class="bi bi-person-circle fs-5"></i> <?php echo $lang['sign_in']; ?></a>
                  </li>
                  <?php } else{ ?>
                  <li class="nav-item">
                    <a class="nav-link btn btn-sign-in" href="<?php echo $base_url; ?>/panel"><i class="bi bi-person-circle fs-5"></i> <?php echo $lang['my_panel']; ?></a>
                  </li>
                  <?php if(!$config->free_mode){?>
                  <a class="text-decoration-none" href="<?php echo $base_url; ?>/panel"><span class="my-credits"><?php echo $lang['my_credits']; ?>: <?php echo number_format($userCredits, 0, '.', ','); ?></span></a>
                  <?php } ?>
                  <?php } ?>
                </ul>
              </div>
            </div>
          </nav>

          <?php if(isset($config->allow_dark_mode) && $config->allow_dark_mode){ ?>
          <div class="theme-icon" id="toggle-button">
              <i class="bi bi-sun fs-4" id="theme-icon"></i>            
          </div>
          <?php } ?>

        </div>
      </div>
    </div>
  </header>
  <?php } ?>