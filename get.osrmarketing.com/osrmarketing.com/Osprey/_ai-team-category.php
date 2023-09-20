<?php 
$header_min = true;
$loadAI = false;
$use_bootstrap_icons = true;
require_once("inc/includes.php");
if(isset($_REQUEST['slug']) && $_REQUEST['slug']){
  $slug = addslashes($_REQUEST['slug']);
  $getCategory = $categories->getBySlug($slug);
  if(!$getCategory){
    redirect($base_url.'/404', "", 'error');
  }
  $getCategories = $categories->getListFront();
  $getAiByCategory = $prompts_categories->getListByIdCategory($getCategory->id);
  $ids_prompts = array();
  foreach ($getAiByCategory as $show) {
    $ids_prompts[] = $show->id_prompt;
  }  
  $ids = implode(',', $ids_prompts);
  if($ids){
    $getPrompts = $prompts->getListByIDS($ids);
  }
}else{
  redirect($base_url.'/404', "", 'error');
}
$getMenuName = $menus->get(2);

define('META_TITLE', $seoConfig['ai_team_meta_title_filter']." - ".$getCategory->name);
define('META_DESCRIPTION', $seoConfig['ai_team_meta_description_filter']." - ".$getCategory->name);
require_once("inc/header.php");
?>

<section id="inner-page">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6"><h1 class="mb-0"><?php echo $getMenuName->name; ?> - <?php echo $getCategory->name; ?></h1></div>
      <div class="col-md-6 text-end">
            <form class="form-filter" id="categoryForm" action="">
                <div class="col-auto">
                    <select class="form-control" id="categoryAI">
                        <option value=""><?php echo $lang['category_select_option']; ?></option>
                        <?php foreach ($getCategories as $showCategories) { ?>
                            <option <?php if($getCategory->id == $showCategories->id) echo "selected"; ?> value="<?php echo $base_url.'/ai-team/'.$showCategories->slug; ?>"><?php echo $showCategories->name; ?> (<?php echo $showCategories->amount_prompt; ?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary d-flex"><i class="bi bi-funnel"></i> <span class="d-none d-lg-block"><?php echo $lang['category_filter']; ?></span></button>
                </div>
            </form>
      </div>
    </div>
  </div>  
</section>

  <section>
    <div class="container">

      <?php if(isset($getPrompts) && $getPrompts){?>
      <div class="row mt-5">
        <?php foreach ($getPrompts as $showPrompts){?>
          <div class="col-lg-3 col-md-4">
            <div class="card-ai <?php echo isset($theme_skin['ai_card_style']) ? $theme_skin['ai_card_style'] : ''; ?> d-grid">
              <?php showVipCard($showPrompts->id); ?> 
              <div class="card-ai-image"><a href="<?php echo $base_url; ?>/chat/<?php echo $showPrompts->slug; ?>"><img loading="lazy"  src="<?php echo $base_url; ?>/public_uploads/<?php echo $showPrompts->image; ?>" onerror="this.src='<?php echo $base_url; ?>/img/no-image.svg'" alt="<?php echo $showPrompts->name; ?>" title="<?php echo $showPrompts->name; ?>"></a></div>
              <div class="card-ai-bottom">
                <div class="card-ai-name"><h3><?php echo $showPrompts->name; ?></h3></div>
                <div class="card-ai-job"><span><?php echo $showPrompts->expert; ?></span></div>
                <a href="<?php echo $base_url; ?>/chat/<?php echo $showPrompts->slug; ?>"><span class="btn btn-primary btn-md start-chat"><i class="bi bi-chat"></i> <?php echo $lang['chat_now']; ?></span></a>
              </div>
            </div>
          </div>
          
        <?php } ?>
      </div>
    <?php } else { ?>
    
    <div class="row text-center mt-4">
      <img src="<?php echo $base_url; ?>/img/result_not_found.svg" alt="No AI found for this category" height="250" class="mb-3">
      <div class="alert alert-light"><i class="bi bi-exclamation-octagon"></i> <?php echo $lang['category_filter_no_results']; ?></div>
      </div>

    <?php } ?>
    </div>
  </section>

<?php
require_once("inc/footer.php");
?>