<?php 
ob_start();
if(!isset($module_name)){
  $module_name = "dashboard";
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/admin/style/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/admin/style/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/admin/style/dataTables.bootstrap5.min.css">
    <link href="<?php echo $base_url; ?>/admin/style/app.css?v=2" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <?php if(isset($use_select2) && $use_select2){?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <?php } ?>
    <?php if(isset($use_codemirror) && $use_codemirror){?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.61.1/codemirror.min.css">
    <?php } ?>
    <meta name="theme-color" content="#1995f0">
  </head>
  <body>

  <?php if(isset($use_save_absolute) && $use_save_absolute){?>
  <div class="btn-save-absolute <?php echo @$_REQUEST['action'] == "edit" ? "submit-button-ajax" : ""; ?>">
    <span><i class="bi bi-save fs-5"></i></span>
  </div>
  <?php } ?>

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Deletion confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this item? This operation is irreversible.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="#" id="deleteConfirmButton" class="btn btn-danger">Delete</a>
        </div>
      </div>
    </div>
  </div>

<header class="navbar navbar-dark navbar-custom sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?php echo $base_url."/admin";?>">Admin CMS</a> 
  <a target="_blank" href="<?php echo $base_url;?>" class="btn btn-sm btn-success me-2 d-none d-lg-block"><i class="bi bi-box-arrow-up-right"></i> View website</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3 menu-overflow">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?php echo $base_url."/admin"; ?>">
              <i class="bi bi-house fs-5"></i>
              Dashboard
            </a>
          </li>

          <?php if (in_array("prompts", json_decode($getUser->permission) ?? [])) { ?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "prompts") echo "active";?>" aria-current="prompts(AI)" href="<?php echo $base_url; ?>/admin/prompts">
              <i class="bi bi-cpu fs-5"></i>
              Prompts (AI)
            </a>
          </li>
          <?php } ?>

          <?php if(in_array("categories", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "categories") echo "active";?>" aria-current="categories" href="<?php echo $base_url; ?>/admin/categories">
              <i class="bi bi-funnel fs-5"></i>
              Prompts Categories
            </a>
          </li>      
          <?php } ?>

          <?php if(in_array("prompts_output", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "prompts_output") echo "active";?>" aria-current="prompts_output" href="<?php echo $base_url; ?>/admin/prompts_output">
              <i class="bi bi-globe fs-5"></i>
              Chat - Output Language
            </a>
          </li>
          <?php } ?>

          <?php if(in_array("prompts_tone", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "prompts_tone") echo "active";?>" aria-current="prompts_tone" href="<?php echo $base_url; ?>/admin/prompts_tone">
              <i class="bi bi-chat-heart fs-5"></i>
              Chat - Tone
            </a>
          </li>
          <?php } ?>

          <?php if(in_array("prompts_writing", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "prompts_writing") echo "active";?>" aria-current="prompts_writing" href="<?php echo $base_url; ?>/admin/prompts_writing">
              <i class="bi bi-chat-left-quote fs-5"></i>
              Chat - Writing Style
            </a>
          </li>          
          <?php } ?>


          <?php if(in_array("customers", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "customers") echo "active";?>" aria-current="Customers" href="<?php echo $base_url; ?>/admin/customers">
             <i class="bi bi-people fs-5"></i>
              Customers
            </a>
          </li>
          <?php } ?>


          <?php if(in_array("settings", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "settings") echo "active";?>" aria-current="settings" href="<?php echo $base_url; ?>/admin/settings">
              <i class="bi bi-gear fs-5"></i>
              Settings
            </a>
          </li>
          <?php } ?>

          <?php if(in_array("sales", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "sales") echo "active";?>" aria-current="sales" href="<?php echo $base_url; ?>/admin/sales">
              <i class="bi bi-receipt fs-5"></i>
              Sales
            </a>
          </li> 
          <?php } ?>

          <?php if(in_array("credits_packs", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "credits_packs") echo "active";?>" aria-current="credits_packs" href="<?php echo $base_url; ?>/admin/credits_packs">
              <i class="bi bi-box fs-5"></i>
              Credits Pack
            </a>
          </li>         
          <?php } ?>

          <?php if(in_array("analytics", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "analytics") echo "active";?>" aria-current="analytics" href="<?php echo $base_url; ?>/admin/analytics">
              <i class='bi bi-bar-chart fs-5'></i>
              Analytics
            </a>
          </li>  
          <?php } ?>



          <?php if(in_array("posts", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "posts") echo "active";?>" aria-current="Posts" href="<?php echo $base_url; ?>/admin/posts">
              <i class="bi bi-sticky fs-5"></i>
              Posts
            </a>
          </li>
          <?php } ?>

          <?php if(in_array("tags", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "tags") echo "active";?>" aria-current="Tags" href="<?php echo $base_url; ?>/admin/tags">
              <i class="bi bi-bookmark-star fs-5"></i>
              Tags
            </a>
          </li>
          <?php } ?>         



          <?php if(in_array("users", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "users") echo "active";?>" aria-current="users" href="<?php echo $base_url; ?>/admin/users">
              <i class="bi bi-person-gear fs-5"></i>
              Admin users
            </a>
          </li>
          <?php } ?>          

          <?php if(in_array("languages", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "languages") echo "active";?>" aria-current="Languages" href="<?php echo $base_url; ?>/admin/languages">
              <i class="bi bi-translate fs-5"></i>
              Translate
            </a>
          </li>
          <?php } ?>


          <?php if(in_array("pages", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "pages") echo "active";?>" aria-current="pages" href="<?php echo $base_url; ?>/admin/pages">
              <i class="bi bi-file-earmark fs-5"></i>
              Pages
            </a>
          </li>  
          <?php } ?>

          <?php if(in_array("theme", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "theme") echo "active";?>" aria-current="theme" href="<?php echo $base_url; ?>/admin/theme">
              <i class="bi bi-palette fs-5"></i>
              Theme skin 
            </a>
          </li>
          <?php } ?>

          <?php if(in_array("seo", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "seo") echo "active";?>" aria-current="seo" href="<?php echo $base_url; ?>/admin/seo">
              <i class="bi bi-binoculars fs-5"></i>
              Seo
            </a>
          </li>
          <?php } ?>

          <?php if(in_array("menus", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "menus") echo "active";?>" aria-current="menus" href="<?php echo $base_url; ?>/admin/menus">
              <i class="bi bi-list fs-5"></i>
              Menus 
            </a>
          </li>          
          <?php } ?>


          <?php if(in_array("badwords", json_decode($getUser->permission) ?? [])){?>
          <li class="nav-item">
            <a class="nav-link <?php if($module_name == "badwords") echo "active";?>" aria-current="badwords" href="<?php echo $base_url; ?>/admin/badwords">
              <i class="bi bi-shield-slash fs-5"></i>
              Bad words
            </a>
          </li>
          <?php } ?>          
          
          <li class="nav-item">
            <a class="nav-link" aria-current="logout" href="<?php echo $base_url; ?>/admin/logout">
              <i class="bi bi-box-arrow-right fs-5"></i>
              Logout
            </a>
          </li>
          

        </ul>
        
      </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

    <?php 
    if(isset($module_name)){
      if($module_name != "dashboard" && !in_array($module_name, json_decode($getUser->permission) ?? [])){
        echo '<div class="alert alert-danger mt-3"><i class="bi bi-exclamation-octagon fs-4"></i> You do not have permission to access this.</div>';
        exit();
      }
    }
  ?>