<?php 
$header_min = true;
$no_footer = true;
$use_bootstrap_icons = true;
require_once("customer-restrict.php");
require_once("../../inc/includes.php");
define('META_TITLE', $lang['my_purchases_title']);
define('META_DESCRIPTION', $lang['my_purchases_title']);
require_once("../../inc/header.php");
$getCustomer = $customers->getCustomer($_SESSION['id_customer']);
if($getCustomer->id){
  $getPurchases = $customer_credits_packs->getByIdCustomer($getCustomer->id);
}
?>

<section id="inner-page">
  <div class="container">
    <div class="row">
      <div class="col"><h3><?php echo $lang['my_purchases_title']; ?></h3></div>
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
          <h4 class="mb-3"><?php echo $lang['my_purchases_title']; ?></h4>

          <?php
          if (isset($_SESSION['action']) && !empty($_SESSION['action']) && !empty($_SESSION['action_message'])) {
            echo '<div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> ' . $_SESSION['action_message'] . '</div>';
          }
          ?>
          <div class="overflow-auto" style="max-height:650px">
          <table class="table">
            <thead>
              <tr>
                <th><?php echo $lang['my_purchases_package_name']; ?></th>
                <th><?php echo $lang['my_purchases_credits']; ?></th>
                <th><?php echo $lang['my_purchases_price']; ?></th>
                <th><?php echo $lang['my_purchases_payment_method']; ?></th>
                <th><?php echo $lang['my_purchases_status']; ?></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php 
                $payment_status = array();
                $keys = ['card_declined', 'expired_card', 'incorrect_cvc', 'processing_error', 'incorrect_number', 'processing', 'abandoned_checkout', 'succeeded','awaiting_payment'];

                foreach ($keys as $key) {
                    if (isset($lang[$key])) {
                        $payment_status[$key] = $lang[$key];
                    }
                }

                foreach ($getPurchases as $showPurchases) {
                    $getCreditPack = $credits_packs->get($showPurchases->id_credit_pack);
                    $status = isset($payment_status[$showPurchases->status]) ? $payment_status[$showPurchases->status] : $showPurchases->status;
                ?>
                    <tr class="align-middle">
                        <td class="fw-bold"><?php if(isset($getCreditPack->name) && $getCreditPack->name) echo $getCreditPack->name; ?></td>
                        <td><?php echo number_format($showPurchases->credit_amount, 0, ',', '.'); ?></td>
                        <td><?php echo $showPurchases->price_label; ?></td>
                        <td>
                            <?php
                            if($showPurchases->payment_method == "stripe") {
                                echo "<i class='bi bi-stripe fs-5'></i> ".$lang['my_purchases_stripe'];
                            } else if($showPurchases->payment_method == "paypal") {
                                echo "<i class='bi bi-paypal fs-5'></i> ".$lang['my_purchases_paypal'];
                            } else {
                                echo "<i class='bi bi-bank fs-5'></i> ".$lang['my_purchases_bank_deposit'];
                            }
                            ?>
                        </td>                        
                        <td>
                            <span class="badge rounded-pill <?php if($showPurchases->status === 'succeeded') {echo 'bg-success';} else if($showPurchases->status === 'processing') {echo 'bg-primary';} else {echo 'bg-danger';}?>">
                                <?php echo $status; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo $base_url."/panel/my-purchases/".$showPurchases->id_order; ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> <?php echo $lang['my_purchases_btn_view_more']; ?>
                            </a>
                        </td>
                    </tr>
            <?php } ?>
            </tbody>
          </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>



<?php 
require_once("../../inc/footer.php");
?>