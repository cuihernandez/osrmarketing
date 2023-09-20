<?php
$use_stripe = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");

$json_str = file_get_contents('php://input');
$data = json_decode($json_str);

if (isset($data->id_order)) {
    $id_order = $data->id_order;
} else {
    $error_data = array(
        'error' => 'Id id_order not found',
        'message' => 'Error retrieving payment method'
    );
    echo json_encode($error_data);
    exit();
}

$id_order = $data->id_order;
$getPaymentDetails = $customer_credits_packs->getByIdOrder($id_order);
$getCreditPack = $credits_packs->get($getPaymentDetails->id_credit_pack);
$getCustomer = $customers->get($getPaymentDetails->id_customer);

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
			    $error_data = array(
			        'error' => 'Error retrieving payment method',
			        'message' => $e->getMessage()
			    );
			    echo json_encode($error_data);
			    exit();
			}
        } else {
			    $error_data = array(
			        'error' => 'PaymentMethod not found',
			        'message' => 'PaymentMethod not found'
			    );
			    $checkout_status = $getPaymentDetails->status;

        }

        // Recupera o objeto Charge
        $chargeId = $paymentIntentFound->latest_charge;
        $charge = \Stripe\Charge::retrieve($chargeId);
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
if($getPaymentDetails->payment_method == "bank_deposit"){
   $checkout_status = $getPaymentDetails->status;
}

$data = array();
if(isset($payment_intent->id)) {
    $data['id'] = $payment_intent->id;
}
$data['id_order'] = $getPaymentDetails->id_order;
$data['customer_name'] = $getCustomer->name;
if($config->demo_mode){
    $data['customer_email'] = '<td><span class="badge bg-danger">Email will not be shown in demo mode</span></td>';
}else{
    $data['customer_email'] = $getCustomer->email;
}

$data['payment_method'] = $getPaymentDetails->payment_method;
$data['item'] = $getCreditPack->name;
$data['price'] = $getPaymentDetails->price_label;
$data['credits'] = number_format($getPaymentDetails->credit_amount, 0, ',', '.');
$data['purchase_date'] = $getPaymentDetails->purchase_date;
$data['claimed'] = $getPaymentDetails->claimed;
$data['price_amount'] = $getPaymentDetails->price_amount;
$data['price_label'] = $getPaymentDetails->price_label;
$data['credit_amount'] = $getPaymentDetails->credit_amount;
$data['price_currency_code'] = $getPaymentDetails->price_currency_code;
if(isset($payment_method_type)) {
    $data['payment'] = $payment_method_type . " - " . $payment_method_card;
}
$data['status'] = $checkout_status;

echo json_encode($data);
?>