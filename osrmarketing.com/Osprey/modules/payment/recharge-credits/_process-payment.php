<?php 
$use_stripe = true;
require_once("../../../inc/includes.php");

$credit_pack_id = (int) addslashes($_REQUEST['id']);
$checkCreditPack = $credits_packs->get($credit_pack_id);

if(!$checkCreditPack->id){
  redirect($base_url.'/pricing', $lang['package_no_exists'], 'error');
}

if(!isset($_SESSION['id_customer']) || is_null($_SESSION['id_customer'])) {
    if(!isset($_SESSION['buy_credit_id'])){
        $_SESSION['buy_credit_id'] = $checkCreditPack->id;
        $_SESSION['payment_method'] = $_GET['payment_method'];
    }  
    redirect($base_url.'/request-account', "", 'error');    
    die();
}
$unique_value = time() . mt_rand(); 
$md5_hash = md5($unique_value);


$getCustomer = $customers->get($_SESSION['id_customer']);


if(isset($_GET['payment_method'])){

    //Set fields to checkout
    $_POST['id_customer'] = $_SESSION['id_customer'];
    $_POST['id_order'] = $md5_hash;
    $_POST['id_credit_pack'] = $checkCreditPack->id;
    $_POST['price_label'] = $checkCreditPack->price;
    $_POST['price_currency_code'] = $checkCreditPack->currency_code;
    $_POST['price_amount'] = $checkCreditPack->amount;
    $_POST['credit_amount'] = $checkCreditPack->credit;

  //Payment by stripe
  if($_GET['payment_method'] == "stripe"){

    try {
        $customer = \Stripe\Customer::create([
            'name' => $getCustomer->name,
            'email' => $getCustomer->email,
        ]);

        // Cria os produtos no Stripe
        $thumb = $base_url."/public_uploads/".$checkCreditPack->image;

        $desc = json_decode($checkCreditPack->description);
        $htmlDescription = "";

        foreach ($desc as $showDescription) {
            $htmlDescription .= $showDescription . ". ";
        }

        if(!$htmlDescription){
            $htmlDescription = $checkCreditPack->name;
        }

        $stripe_product = \Stripe\Product::create([
            'name' => $checkCreditPack->name,
            'description' => $htmlDescription,
            'images' => [$thumb],
        ]);

        $stripe_product_price = \Stripe\Price::create([
            'product' => $stripe_product->id,
            'unit_amount' => $checkCreditPack->amount,
            'currency' => strtolower($checkCreditPack->currency_code),
        ]);

        $session = \Stripe\Checkout\Session::create([
            'customer' => $customer->id,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $stripe_product_price->id,
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'client_reference_id' => $getCustomer->id,
            'allow_promotion_codes' => false,
            'success_url' => $base_url."/panel/checkout-complete?id_order=".$md5_hash,
            'cancel_url' => $base_url."/panel/checkout-complete?id_order=".$md5_hash,
            'payment_intent_data' => [
                'metadata' => [
                    'id_order' => $md5_hash,
                    'price_id' => $stripe_product_price->id,
                    'product_id' => $stripe_product->id,      
                ],
            ],  
        ]);

        $_POST['status'] = "processing";
        $_POST['payment_method'] = "stripe";


        //$customer_credits_packs->debug(true);
        if($customer_credits_packs->add()){
          header("Location: " . $session->url);
          exit;
        }else{
          redirect($base_url.'/pricing', $lang['error_checking_out'], 'error');
        }

    } catch (\Stripe\Exception\AuthenticationException $e) {
        displayError($e->getMessage());
        die();
    } catch (\Exception $e) {
        displayError($e->getMessage());
        die();
    }
     

  }//end stripe


  //Payment by bank deposit
  if($_GET['payment_method'] == "bank_deposit"){

    $_POST['status'] = "awaiting_payment";
    $_POST['payment_method'] = "bank_deposit";

    
    if($customer_credits_packs->add()){
      redirect($base_url.'/panel/my-purchases/'.$md5_hash, $lang['order_successfully_deposit'], 'success');
    }else{
      redirect($base_url.'/pricing', $lang['error_checking_out'], 'error');
    }

  }//end bank deposit


// Payment by paypal
if ($_GET['payment_method'] == "paypal") {
    $response = createOrder($config, $md5_hash, $checkCreditPack,$base_url);

    if ($response['id']) {
        $_POST['status'] = "awaiting_payment";
        $_POST['payment_method'] = "paypal";
        $_POST['paypal_token'] = $response['id'];

        $checkPaypalToken = $customer_credits_packs->getByPaypalToken($_POST['paypal_token']);

        if ($checkPaypalToken->paypal_token) {
            redirect($base_url . '/panel/my-purchases/' . $checkPaypalToken->id_order, $lang['order_successfully_deposit'], 'success');
        } else {
            if ($customer_credits_packs->add()) {
                if (isset($response['links'])) {
                    foreach ($response['links'] as $link) {
                        if ($link['rel'] == 'payer-action') {
                            header("Location: " . $link['href']);
                            exit();
                        }
                    }
                }
            } else {
                redirect($base_url . '/pricing', $lang['error_checking_out'], 'error');
            }
        }
    }else{
        echo $response['message'];
        die();
    }
} //end paypal


}else{
  redirect($base_url.'/pricing', $lang['error_payment_not_defined'], 'error');
}