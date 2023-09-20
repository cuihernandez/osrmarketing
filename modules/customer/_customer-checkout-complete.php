<?php 
ob_start();
$no_header = true;
$no_footer = true;
$use_stripe = true;
require_once("../../inc/includes.php");
$id_order = addslashes($_REQUEST['id_order']);

$checkPaymentOrigin = $customer_credits_packs->getByIdOrder($id_order);

//Stripe
if($checkPaymentOrigin->payment_method == "stripe"){
  $paymentIntentFound = findPaymentIntent($config, $id_order);
  if (!$paymentIntentFound) {
    redirect($base_url.'/panel/my-purchases/'.$id_order, $lang['checkout_complete_purchase_not_found'], 'error');
  }else{
      // Busca o pagamento com o ID da transação
      $payment_intent = \Stripe\PaymentIntent::retrieve($paymentIntentFound->id);
      $getPaymentDetails = $customer_credits_packs->getByIdOrder($id_order);

      if($payment_intent->status === "succeeded"){
        processSitePayment($checkPaymentOrigin);
      }else{
        // Recupera o objeto Charge
        $chargeId = $paymentIntentFound->latest_charge;
        $charge = \Stripe\Charge::retrieve($chargeId);
        $customer_credits_packs->setCheckoutStatus($id_order,$charge->failure_code);
      }

    }
}

//Paypal
if($checkPaymentOrigin->payment_method == "paypal"){

  $checkPayment = processPayment($_GET['token']);
    switch($checkPayment['status']) {
        
        case "APPROVED":
            $checkout_status = "awaiting_payment"; 
            $capturePayment = capturePayment($_GET['token']);
            if(isset($capturePayment['details']) && $capturePayment['details'][0]["issue"] == "INSTRUMENT_DECLINED"){
              $customer_credits_packs->setCheckoutStatus($id_order,"card_declined");
            }else{
              // Check capture status
              if(isset($capturePayment['status']) && $capturePayment['status'] == "COMPLETED") {
                  processSitePayment($checkPaymentOrigin);
                  $customer_credits_packs->setCheckoutStatus($id_order,"succeeded");
              }
            }
            break;

        case "COMPLETED":
            processSitePayment($checkPaymentOrigin);
            $customer_credits_packs->setCheckoutStatus($id_order,"succeeded");
            break;

        default:
            $checkout_status = "awaiting_payment"; 
            break;
    }
}
header("Refresh: 2; URL=".$base_url.'/panel/my-purchases/'.$id_order);
define('META_TITLE', $lang['checkout_complete_title']);
define('META_DESCRIPTION', $lang['checkout_complete_title']);  
require_once("../../inc/header.php");
?>

<section id="panel-area">
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="white-card content-panel text-center">
          <h4 class="mb-3"><?php echo $lang['checkout_complete_title']; ?></h4>
          <p><?php echo $lang['checkout_complete_redirect']; ?></p>
          <div class="spinner-border text-success" role="status">
            <span class="visually-hidden"><?php echo $lang['checkout_complete_loading']; ?></span>
          </div>          
        </div>
      </div>
    </div>
  </div>
</section>

<?php 
require_once("../../inc/footer.php");
ob_end_flush();
?>