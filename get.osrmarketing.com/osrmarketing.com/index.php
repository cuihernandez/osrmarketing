<?php 
$use_bootstrap_icons = true;
// Include necessary files
require_once(__DIR__ . '/inc/includes.php');
define('META_TITLE', $seoConfig['home_meta_title']);
define('META_DESCRIPTION', $seoConfig['home_meta_description']);
require_once(__DIR__ . '/inc/header.php');
// Set the prompts list
$getPrompts = $prompts->getListFront();
// Flag to determine if bootstrap icons should be used
$use_bootstrap_icons = true;
$getCategories = $categories->getListFrontLimit($config->display_categories_home_number);
?>

<!-- Start of the Hero Section -->
<section id="hero" class="align-items-center hide-section" style="background: url(<?php echo $base_url."/public_uploads/".$getTheme->image_hero_background; ?>);">
    <!-- Hero Content -->
    <div class="container">
        <div class="row align-items-center">
            <!-- Hero Text -->
            <div class="col-lg-6 col-md-12">
                <h1><?php echo $lang['main_title']; ?></h1>
                <p class="translate-sub-title"><?php echo $lang['sub_title']; ?></p>
                <a href="#ai-team" class="btn btn-lg translate-button-header-cta"><?php echo $lang['button_header_cta']; ?></a>
            </div>
            <!-- Hero Image -->
            <div class="col-lg-6 col-md-12 d-flex justify-content-lg-end justify-content-md-center justify-content-sm-center hero-call-action-img">
                <img src="<?php echo $base_url."/public_uploads/".$getTheme->image_hero; ?>" alt="<?php echo $lang['company_name']; ?>" title="<?php echo $lang['company_name']; ?>">
            </div>
        </div>
    </div> 
</section>

<!-- Start of the AI Team Section -->
<section id="ai-team">
    <div class="container section-spacing">
        <div class="row">
            <!-- AI Team Title -->
            <div class="col text-center">
                <h2 class="default-title"><?php echo $lang['body_title_cta']; ?></h2>
            </div>
        </div>


        <div class="row">
            <!-- AI Team Subtitle -->
            <div class="col text-center">
                <p><?php echo $lang['body_sub_title']; ?></p>
            </div>
        </div>

        <div class="row relative mt-3">
            <?php 
            $showLimit = $config->display_home_ai_number;
            $count = 0;
            foreach ($getPrompts as $showPrompts) { 
                if ($count >= $showLimit && !$config->display_load_more_button) break;
                $extraCardClass = ($count >= $showLimit && $config->display_load_more_button) ? 'hidden-card' : '';
            ?>
                <div class="col-lg-3 col-md-4 <?php echo $extraCardClass; ?>">
                    <div class="card-ai <?php echo isset($theme_skin['ai_card_style']) ? $theme_skin['ai_card_style'] : ''; ?> d-grid">
                        <?php showVipCard($showPrompts->id); ?>
                        <div class="card-ai-image">
                            <a href="<?php echo $base_url ?>/chat/<?php echo $showPrompts->slug ?>">
                                <img <?php echo ($count < $showLimit) ? 'src="'.$base_url.'/public_uploads/'.$showPrompts->image.'"' : 'data-src="'.$base_url.'/public_uploads/'.$showPrompts->image.'"' ?> loading="lazy" onerror="this.onerror=null;this.src='<?php echo $base_url ?>/img/no-image.svg'" alt="<?php echo $showPrompts->name ?>" title="<?php echo $showPrompts->name ?>">
                            </a>
                        </div>
                        <div class="card-ai-bottom">
                            <div class="card-ai-name">
                                <h3><?php echo $showPrompts->name ?></h3>
                            </div>
                            <div class="card-ai-job">
                                <span><?php echo $showPrompts->expert ?></span>
                            </div>
                            <a href="<?php echo $base_url ?>/chat/<?php echo $showPrompts->slug ?>">
                                <span class="btn btn-primary btn-md start-chat"><i class="bi bi-chat"></i> <?php echo $lang['chat_now']; ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                $count++; 
            } // end foreach ?>

            <?php if($config->display_load_more_button && $count > $showLimit){?>
            <div class="col-12 text-center mt-2">
                <button id="showAll" class="btn btn-lg btn-dark"><i class="bi bi-arrow-clockwise"></i> <?php echo $lang['ai_show_more_button']; ?></button>
            </div>
            <?php } ?>
        </div>

        <?php if($config->display_categories_home){?>
        <div class="row mt-5">
            <hr>
            <h3 class="title-default text-center mb-3 mt-4"><?php echo $lang['category_home_label']; ?></h3>
            <div class="col-12 pb-2 pt-2">
               <div class="wrapper-category">
                  <?php foreach ($getCategories as $showCategories) {
                   
                      $categoryLink = $base_url . '/ai-team/' . $showCategories->slug;
                      $categoryName = $showCategories->name;
                      ?>
                      <div class="wrapper-category-item">
                        <a href="<?php echo $categoryLink; ?>" class="nav-link">
                            <img src="<?php echo $base_url."/public_uploads/".$showCategories->image; ?>" alt="<?php echo $categoryName; ?>" title="<?php echo $categoryName; ?>" onerror="this.src='https://placehold.co/150x150'">
                            <span><?php echo $categoryName; ?></span>
                        </a>
                      </div>
                    
                  <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>            

    </div>
</section>
<!-- End of the AI Team Section -->



<?php 
// Clean up the session if necessary
if (isset($_SESSION['buy_credit_id'])) {
    unset($_SESSION['buy_credit_id']);
};

// Include the footer
require_once("inc/footer.php");
?>