<?php 
$header_min = true;
$loadAI = false;
$use_bootstrap_icons = true;
require_once("inc/includes.php");
define('META_TITLE', $seoConfig['pricing_meta_title']);
define('META_DESCRIPTION', $seoConfig['pricing_meta_description']);
require_once("inc/header.php");
$getCreditsPacks = $credits_packs->getListFront()->FetchAll();
$getMenuName = $menus->get(3);

if($config->display_prompts_packagelist){
  $highTier = $config->vip_higher_tier;
  $organized_vip_prompts = array();
  $free_prompts = array_map(function($prompt) {
      return array(
          'id' => $prompt->id,
          'name' => $prompt->name,
          'image' => $prompt->image
      );
  }, $prompts_credits_packs->getAllFreePrompts()->FetchAll());

  foreach ($getCreditsPacks as $showCreditsPackArray) {
      $vip_prompts = array_map(function($showP) {
          return array(
              'id' => $showP->id,
              'name' => $showP->name,
              'image' => $showP->image,
              'tier' => $showP->tier
          );
      }, $prompts_credits_packs->getAllFreePromptsByCreditPackId($showCreditsPackArray->id)->FetchAll());
    
      foreach ($vip_prompts as $vip_prompt) {
          $organized_vip_prompts[$vip_prompt['tier']][] = $vip_prompt;
      }
  }

  if ($highTier) {
      $all_tiers = array_keys($organized_vip_prompts);
      sort($all_tiers);

      $accumulated_prompts = array();
      foreach ($all_tiers as $i) {
          if (!empty($organized_vip_prompts[$i])) {
              // Merge and remove duplicates
              $merged = array_merge($accumulated_prompts, $organized_vip_prompts[$i]);
              $unique = array();
              foreach ($merged as $item) {
                  $unique[md5($item['name'].$item['image'])] = $item;
              }
              $organized_vip_prompts[$i] = array_values($unique);
          }
          $accumulated_prompts = $organized_vip_prompts[$i] ?? array();
      }
  }

  foreach ($organized_vip_prompts as $tier => &$prompts) {
      if ($config->vip_display_free_prompts) {
          $free_prompts_tiered = array_map(function($prompt) use ($tier) {
              $prompt['tier'] = $tier;
              return $prompt;
          }, $free_prompts);

          // Merge and remove duplicates
          $merged = array_merge($free_prompts_tiered, $prompts);
          $unique = array();
          foreach ($merged as $item) {
              $unique[md5($item['name'].$item['image'])] = $item;
          }
          $prompts = array_values($unique);
      } else {
          $prompts = $prompts;
      }
  }
  unset($prompts);
}
$stripe_active = $config->stripe_payment_active;
$bank_deposit_active = $config->bank_deposit_active;
$paypal_active = $config->paypal_payment_active;
$payment_methods = [$stripe_active, $bank_deposit_active, $paypal_active];
$single_payment_method = count(array_filter($payment_methods)) === 1;
?>

<section id="inner-page">
  <div class="container">
    <div class="row">
      <div class="col"><h1><?php echo $getMenuName->name; ?></h1></div>
    </div>
  </div>  
</section>

<section class="pricing py-5">
  <div class="container">

    <div class="row">
      <div class="col text-center py-4">
        <h2 class="default-title"><?php echo $lang['price_page_title']; ?></h2>
      </div>
    </div>    

    <?php
      if (isset($_SESSION['action']) && !empty($_SESSION['action'])) {
        echo '<div class="row text-center"><div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> ' . $_SESSION['action_message'] . '</div></div>';
      }
    ?>


    <div class="row">
      <?php 
      foreach ($getCreditsPacks as $showCreditsPack){
      ?>
        <div class="col-lg-4 mb-5 ">
          <div class="card mb-5 mb-lg-0 h-100">
            <div class="card-body d-flex flex-column">
              <div class="card-price-thumb"><img alt="<?php echo $showCreditsPack->name;?>" title="<?php echo $showCreditsPack->name;?>" src="<?php echo $base_url."/public_uploads/".$showCreditsPack->image; ?>"  onerror="this.src='<?php echo $base_url; ?>/img/coin-placeholder.png'"></div>
              <h5 class="card-title text-muted text-uppercase text-center"><?php echo $showCreditsPack->name; ?></h5>
              <h6 class="card-price text-center"><?php echo $showCreditsPack->price; ?></h6>
              <hr>
              <ul>
                <?php
                $desc = json_decode($showCreditsPack->description); 
                  foreach ($desc as $showDescription) {
                    echo "<li>".$showDescription."</li>";
                  }
                ?>
              </ul>
            
            <?php if($config->display_prompts_packagelist){?>
            <div class="package-display-prompts-vip">
              <div class="package-wrapper-prompt">
                <?php
                if (isset($organized_vip_prompts[$showCreditsPack->tier])) {
                  foreach ($organized_vip_prompts[$showCreditsPack->tier] as $prompt) {?>
                      <div class="package-wrapper-prompt-thumb">
                        <img src="<?php echo $base_url."/public_uploads/".$prompt['image']; ?>" alt="<?php echo $prompt['name']; ?>" title="<?php echo $prompt['name']; ?>" onerror="this.src='<?php echo $base_url; ?>/img/no-image.svg'">
                      </div>
                  <?php } ?>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
                      
              <div class="d-grid mt-auto">
                  <button data-id="<?php echo $showCreditsPack->id; ?>" data-href="<?php echo $base_url.'/recharge-credits'; ?>" class="btn btn-primary text-uppercase purchase-btn" <?php if ($single_payment_method) echo 'data-single-payment-method="true"'; ?>><?php echo $lang['price_page_btn_purchase']; ?></button>
                  <div class="payment-options d-none">
                      <?php if ($stripe_active) { ?>
                          <button class="btn btn-success stripe-btn"><i class="bi bi-stripe"></i> <?php echo $lang['price_page_pay_stripe']; ?></button>
                      <?php } ?>
                      <?php if ($bank_deposit_active) { ?>
                          <button class="btn btn-secondary bank-deposit-btn"><i class="bi bi-bank"></i> <?php echo $lang['price_page_pay_bank_deposit']; ?></button>
                      <?php } ?>
                      <?php if ($paypal_active) { ?>
                          <button class="btn btn-primary paypal-btn"><i class="bi bi-paypal"></i> <?php echo $lang['price_page_pay_paypal']; ?></button>
                      <?php } ?>
                      <button type="button" class="close-payment-options"><?php echo $lang['close_payment_method']; ?></button>
                  </div>
              </div>
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