<?php 
$header_min = true;
$bg_white = true;
$loadAI = false;
$use_bootstrap_icons = true;
$blog = true;
require_once("inc/includes.php");

$getCreditsPacks = $credits_packs->getListFront();
$getMenuName = $menus->getBySlug("blog");
$getPost = $posts->getBySlug($_REQUEST['slug']);
// Redirect to 404 page if the slug is not set or if the AI does not exist
if (!isset($_REQUEST['slug']) || !$getPost) {
    header("location:/404");
    exit();
}
$getTags = $tags->getListFront();
define('META_TITLE', $getPost->meta_title);
define('META_DESCRIPTION', $getPost->meta_description);
define('OG_IMAGE', $base_url."/public_uploads/".$getPost->image);
require_once("inc/header.php");
$getPostTags = $posts_tags->getListByIdPost($getPost->id);
?>

<section class="py-5">
  <div class="container blog-container">

    <div class="row">
      <div class="col-lg-8 blog-content-col">
        <div class="post-cover">
          <h1><?php echo $getPost->title; ?></h1>
          <div class="post-tags-list">
          <?php foreach ($getPostTags as $showPostTags) {
            $postTagURL = $tags->get($showPostTags->id_tag);
          ?>
          <a href="<?php echo $base_url."/tag/".$postTagURL->slug; ?>" class="btn btn-primary btn-sm me-2 mb-2"><?php echo $postTagURL->name; ?></a>
          <?php } ?>            
          </div>
          <div class="pb-3 pt-1">
            <span><i class="bi bi-calendar"></i> <?php echo formatDate($getPost->publication_date, false); ?></span>          
          </div>
          <img src="<?php echo $base_url; ?>/public_uploads/<?php echo $getPost->image;?>" onerror="this.src='https://placehold.co/1200x628'" alt="<?php echo $getPost->title;?>" title="<?php echo $getPost->title;?>">

          <div class="share-post">
            <div class="wrapper-share-post">
              <div class="share-post-title">
                <h4>Share with friends:</h4>
              </div>
              <div class="share-post-icons">
                <span data-sharer="twitter" data-url="<?php echo $base_url."/blog/".$getPost->slug; ?>"><i class="bi bi-twitter"></i></span>
                <span data-sharer="facebook" data-url="<?php echo $base_url."/blog/".$getPost->slug; ?>"><i class="bi bi-facebook"></i></span>
                <span data-sharer="linkedin" data-url="<?php echo $base_url."/blog/".$getPost->slug; ?>"><i class="bi bi-linkedin"></i></span>
                <span data-sharer="whatsapp" data-url="<?php echo $base_url."/blog/".$getPost->slug; ?>"><i class="bi bi-whatsapp"></i></span>
                <span data-sharer="telegram" data-url="<?php echo $base_url."/blog/".$getPost->slug; ?>"><i class="bi bi-telegram"></i></span>
                <span data-sharer="pinterest" data-url="<?php echo $base_url."/blog/".$getPost->slug; ?>"><i class="bi bi-pinterest"></i></span>
                <span data-sharer="reddit" data-url="<?php echo $base_url."/blog/".$getPost->slug; ?>"><i class="bi bi-reddit"></i></span>
              </div>
            </div>
          </div>

        </div>
 
        <div class="post-content">
          <?php echo $getPost->content; ?>
        </div>

      </div>
      <div class="col-lg-4">
        <div class="blog-sidebar">
          <h4><?php echo $lang['blog_all_tags_label'];?></h4>
          <div class="wrapper-tags d-flex flex-wrap">
            <?php foreach ($getTags as $showTags) {?>
              <a href="<?php echo $base_url."/tag/".$showTags->slug; ?>" class="btn btn-secondary btn-sm me-2 mb-2"><?php echo $showTags->name; ?></a>
            <?php } ?>
          </div>
        </div>

        <div class="blog-sidebar">
            <?php echo $config->blog_sidebar;?>
        </div>

      </div>
    </div>    
  </div>
</section>


<?php
require_once("inc/footer.php");
?>