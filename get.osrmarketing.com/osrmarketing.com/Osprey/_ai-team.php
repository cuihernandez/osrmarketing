<?php 
$header_min = false;
$loadAI = false;
$use_bootstrap_icons = true;
require_once("inc/includes.php");
define('META_TITLE', $seoConfig['ai_team_meta_title']);
define('META_DESCRIPTION', $seoConfig['ai_team_meta_description']);
require_once("inc/header.php");
$getPrompts = $prompts->getListFront();
$getCategories = $categories->getListFront();
$getMenuName = $menus->get(2);
?>

<section id="inner-page">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6"><h1 class="mb-0"><?php echo $getMenuName->name; ?></h1></div>
      <div class="col-md-6 text-end">
            <form class="form-filter" id="categoryForm" action="">
                <div class="col-auto">
                    <select class="form-control" id="categoryAI">
                        <option value=""><?php echo $lang['category_select_option']; ?></option>
                        <?php foreach ($getCategories as $showCategories) { ?>
                            <option value="<?php echo $base_url.'/ai-team/'.$showCategories->slug; ?>"><?php echo $showCategories->name; ?> (<?php echo $showCategories->amount_prompt; ?>)</option>
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
    <div class="container section-spacing">
      <div class="row">
        <div class="col text-center">
          <h2 class="default-title"><?php echo $lang['ai_team_title']; ?></h2>
        </div>
      </div>

      <div class="row mt-4">
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
    </div>
  </section>

<?php
require_once("inc/footer.php");
?>