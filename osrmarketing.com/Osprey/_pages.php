<?php 
$bg_white = true;
$header_min = true;
$loadAI = false;
$use_bootstrap_icons = true;
require_once("inc/includes.php");
if(isset($_REQUEST['slug']) && $_REQUEST['slug']){
  $slug = addslashes($_REQUEST['slug']);
  $get = $pages->getBySlug($slug);
  if(!$get){
    redirect($base_url.'/404', "", 'error');
  }
}else{
  redirect($base_url.'/404', "", 'error');
}
define('META_TITLE', $get->meta_title);
define('META_DESCRIPTION', $get->meta_description);
require_once("inc/header.php");
?>

<section id="inner-page">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-12"><h1 class="mb-0"><?php echo $get->name; ?></h1></div>
    </div>
  </div>  
</section>

  <section class="section-spacing">
    <div class="container">
      <div class="col-12">
        <?php echo stripslashes($get->content); ?>
      </div>  
    </div>
  </section>

<?php
require_once("inc/footer.php");
?>