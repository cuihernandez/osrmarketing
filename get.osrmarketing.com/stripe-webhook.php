<?php
$use_stripe = true;
require_once("inc/includes.php");
$webhook_secret = null;
if($config->stripe_test_mode){
     $webhook_secret = $config->stripe_webhook_secret_test;
}else{
     $webhook_secret = $config->stripe_webhook_secret_production;
}

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $webhook_secret
  );
} catch(\UnexpectedValueException $e) {
  echo $e->getMessage();
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  echo $e->getMessage();
  http_response_code(400);
  exit();
}

// Handle the event
switch ($event->type) {
    case 'payment_intent.succeeded':
        // Logic for successful payment
        $id_order = $event->data->object->metadata->id_order;
        $getPaymentDetails = $customer_credits_packs->getByIdOrder($id_order);

        // If the payment is successfully completed
        if((int) $getPaymentDetails->claimed == 0){
          $customer_credits_packs->checkoutSuccess($getPaymentDetails->id_customer, $getPaymentDetails->id_order, $getPaymentDetails->credit_amount);
        }
        break;
    case 'payment_intent.payment_failed':
        // Logic for failed payment
        $id_order = $event->data->object->metadata->id_order;
        $failure_code = $event->data->object->last_payment_error->code;
        $customer_credits_packs->setCheckoutStatus($id_order, $failure_code);
        break;
    // Add other cases as necessary to handle other event types
    default:
        // Ignore other types of events
        break;
}
http_response_code(200);
?>