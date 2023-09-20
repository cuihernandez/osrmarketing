<?php 
$use_confetti = true;
$header_min = true;
$no_footer = true;
$use_bootstrap_icons = true;
$use_stripe = true;
require_once("../../inc/includes.php");
require_once("customer-restrict.php");
$id_order = addslashes($_REQUEST['id_order']);
$getPaymentDetails = $customer_credits_packs->getByIdOrder($id_order);
$checkout_status = "awaiting_payment";
if(!$getPaymentDetails || (isset($_SESSION['id_customer']) && $_SESSION['id_customer'] != $getPaymentDetails->id_customer)){
  redirect($base_url.'/panel', "Purchase not found", 'error');
}
define('META_TITLE', $lang['my_purchases_details_title']);
define('META_DESCRIPTION', $lang['my_purchases_details_title']);
require_once("../../inc/header.php");

$getCreditPack = $credits_packs->get($getPaymentDetails->id_credit_pack);

//Stripe payment
if($getPaymentDetails->payment_method == "stripe"){
    $paymentIntentFound = findPaymentIntent($config, $id_order);

    if($paymentIntentFound){
        // Busca o pagamento com o ID da transação
        $payment_intent = \Stripe\PaymentIntent::retrieve($paymentIntentFound->id);
        // Imprime as informações do pagamento
        if ($payment_intent->payment_method) {
            try {
                // Imprime as informações do pagamento
                $payment_method = \Stripe\PaymentMethod::retrieve($payment_intent->payment_method);
                $payment_method_type =  $payment_method->type;
                $payment_method_card = $payment_method->card->brand . ' ' . $payment_method->card->last4;

            } catch (\Stripe\Exception\ApiErrorException $e) {
                redirect($base_url.'/panel/my-purchases', "Error retrieving payment method:".$e->getMessage(), 'error');
            }
        } else {
            //Payment method 404
        }
        // Recupera o objeto Charge
        $chargeId = $paymentIntentFound->latest_charge;
        $charge = \Stripe\Charge::retrieve($chargeId);


        if($payment_intent->status === "succeeded"){
          if((int) $getPaymentDetails->claimed == 0){
            $customer_credits_packs->checkoutSuccess($getPaymentDetails->id_customer, $getPaymentDetails->id_order, $getPaymentDetails->credit_amount);
            header('Location: ' . $base_url."/panel/my-purchases/".$getPaymentDetails->id_order);
            exit();
          }
        }

        $checkout_status = $payment_intent->status;
    }else{
        if($getPaymentDetails->payment_method == "stripe"){
          $checkout_status = "abandoned_checkout";
          $customer_credits_packs->setCheckoutStatus($id_order,$checkout_status);
        }
    }

    if(isset($charge->failure_code) && $charge->failure_code){
      $checkout_status = $charge->failure_code;
      $customer_credits_packs->setCheckoutStatus($id_order,$charge->failure_code);
    }
}



//Bank deposit
if ($getPaymentDetails->payment_method == "bank_deposit") {
    $checkout_status = $getPaymentDetails->status;
}

//Paypal
if ($getPaymentDetails->payment_method == "paypal") {
    $checkPayment = processPayment($getPaymentDetails->paypal_token);
    $paypalCheckoutLink = "";
    switch($checkPayment['status']) {
        case "PAYER_ACTION_REQUIRED":
            $checkout_status = "awaiting_payment"; 
            $paypalCheckoutLink = $checkPayment['links'][1]['href'];
            $capturePayment = capturePayment($getPaymentDetails->paypal_token);
            break;

        case "APPROVED":
            $checkout_status = "awaiting_payment"; 
            $capturePayment = capturePayment($getPaymentDetails->paypal_token);
            if($capturePayment['details'][0]["issue"] == "INSTRUMENT_DECLINED"){
              $checkout_status = "card_declined";
            }else{
              // Check capture status
              if($capturePayment['status'] == "COMPLETED") {
                  $checkout_status = "succeeded"; 
                  $customer_credits_packs->setCheckoutStatus($id_order,$checkout_status);
                  processSitePayment($getPaymentDetails);
              }
            }
            break;

        case "COMPLETED":
            $checkout_status = "succeeded";
            $customer_credits_packs->setCheckoutStatus($id_order,$checkout_status);
            processSitePayment($getPaymentDetails);
            break;

        default:
            $checkout_status = "awaiting_payment"; 
            break;
    }
}

//Translate status
$payment_status = array();
$keys = ['card_declined', 'expired_card', 'incorrect_cvc', 'processing_error', 'incorrect_number', 'processing', 'abandoned_checkout', 'succeeded','awaiting_payment'];
foreach ($keys as $key) {
    if (isset($lang[$key])) {
        $payment_status[$key] = $lang[$key];
    }
}
$checkout_status_label = isset($payment_status[$checkout_status]) ? $payment_status[$checkout_status] : '';
?>

<section id="inner-page">
  <div class="container">
    <div class="row">
      <div class="col"><h3><?php echo $lang['my_purchases_details_title']; ?></h3></div>
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
          <div class="row">
            <div class="col-5 col-md-4 col-lg-4">
              <h4 class="mb-3"><?php echo $lang['my_purchases_details_title']; ?></h4>
            </div>
            <div class="col-md-8 col-lg-8 col-12">
              <a class="btn btn-danger d-block float-md-end mb-2 mb-md-0" href="<?php echo $base_url."/panel/my-purchases";?>"><i class="bi bi-arrow-left"></i> <?php echo $lang['btn_customer_back']; ?></a>
              <button class="btn d-block btn-success d-block float-md-end me-md-3" onclick="location.reload()"><i class="bi bi-arrow-clockwise"></i> <?php echo $lang['btn_customer_refresh_page']; ?></button>
            </div>
          </div>

          <?php if(isset($charge) && $charge->failure_code){?>
          <div class="alert alert-danger"><?php echo $lang['message_payment_declined']." - ".$charge->failure_code." - ".$charge->failure_message; ?></div>
          <?php } ?>

          <?php if(isset($payment_intent) && $payment_intent->status === "succeeded" && !$charge->refunded){?>
          <div class="alert alert-success"><?php echo $lang['message_payment_approved']; ?></div>
          <?php } ?>

          <?php if(isset($payment_intent) && $payment_intent->status && $charge->refunded){?>
          <div class="alert alert-warning"><?php echo $lang['message_payment_refunded']; ?></div>
          <?php } ?>

          <?php
          if (isset($_SESSION['action']) && !empty($_SESSION['action'])) {
            if ($_SESSION['action'] === 'success') {
              echo '<div class="container pt-lg-3"><div class="row"><div class="alert alert-success"><i class="bi bi-check2-circle"></i> ' . $_SESSION['action_message'] . '</div></div></div>';
            } else {
              echo '<div class="container pt-lg-3"><div class="row"><div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> ' . $_SESSION['action_message'] . '</div></div></div>';
            }
          }
          ?>          

          <div>
            <ul class="list-group list-group-flush">
              <?php if(isset($payment_intent->id)): ?>
              <li class="list-group-item"><strong>ID:</strong> <?php echo $payment_intent->id; ?></li>
              <?php endif; ?>             
              <li class="list-group-item">
                  <strong><?php echo $lang['my_purchases_payment_method']; ?>: </strong>
                  <?php
                  if($getPaymentDetails->payment_method == "stripe") {
                      echo "<i class='bi bi-stripe fs-5'></i> ".$lang['my_purchases_stripe'];
                  } else if($getPaymentDetails->payment_method == "paypal") {
                      echo "<i class='bi bi-paypal fs-5'></i> ".$lang['my_purchases_paypal'];
                  } else {
                      echo "<i class='bi bi-bank fs-5'></i> ".$lang['my_purchases_bank_deposit'];
                  }
                  ?>
              </li>   
              <?php if(isset($getCreditPack->name) && $getCreditPack->name){?>           
              <li class="list-group-item"><strong><?php echo $lang['my_purchases_package_name']; ?>: </strong> <?php echo $getCreditPack->name; ?></li>
              <?php } ?>
              <li class="list-group-item"><strong><?php echo $lang['my_purchases_price']; ?>: </strong> <?php echo $getPaymentDetails->price_label; ?></li>
              <li class="list-group-item"><strong><?php echo $lang['my_purchases_credits']; ?>:</strong> <?php echo $getPaymentDetails->credit_amount; ?></li>
              <?php if(isset($payment_method_type)): ?>
                <li class="list-group-item"><strong><?php echo $lang['my_purchases_payment_method']; ?>:</strong> <?php echo $payment_method_type." - ".$payment_method_card; ?></li>
              <?php endif; ?>              
              <li class="list-group-item">
                <strong><?php echo $lang['my_purchases_status']; ?>:</strong> <?php echo $checkout_status_label; ?>
                <?php if(isset($paypalCheckoutLink) && $paypalCheckoutLink){
                  echo "<div class='mt-1'><a target='_blank' class='btn btn-primary' href='".$paypalCheckoutLink."'><i class='bi bi-paypal'></i> ".$lang['price_page_pay_paypal']."</a></div>";
                }?>
              </li>
            </ul>  

              <?php if($getPaymentDetails->payment_method == "bank_deposit" && $getPaymentDetails->status != "succeeded"): ?>
                <div class="col-12 mt-2">
                  <div class="deposit-info-area">
                   <?php echo nl2br($config->bank_deposit_info); ?>
                  </div>           
                </div>     
              <?php endif; ?>              

          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<?php if(isset($checkout_status) && $checkout_status == "succeeded"){?>
  <script src="<?php echo $base_url; ?>/js/confetti.js"></script>
  <script type="text/javascript">confetti.start(3000);</script>
<?php } ?>


<?php 
require_once("../../inc/footer.php");
?>