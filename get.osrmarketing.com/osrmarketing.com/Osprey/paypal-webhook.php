<?php
require_once("inc/includes.php");
$rawData = file_get_contents('php://input');
//file_put_contents('request_log.txt', $rawData);

$data = json_decode($rawData, true);
if (isset($data['event_type'])) {
    switch($data['event_type']) {
        case 'CHECKOUT.ORDER.APPROVED':
            // Armazenar os dados relevantes em variáveis
            $orderIdPaypal = $data['resource']['id'];
            $orderStatus = $data['resource']['status'];
            $checkBuy = $customer_credits_packs->getByPayPalId($orderIdPaypal);


            if($orderStatus == "COMPLETED"){
                if($checkBuy->id){
                    processSitePayment($checkBuy);
                    $customer_credits_packs->setCheckoutStatus($checkBuy->id_order,"succeeded");
                }
            }

            if($orderStatus == "APPROVED"){
                $checkout_status = "awaiting_payment";
                $capturePayment = capturePayment($orderIdPaypal);
                if(isset($capturePayment['details']) && $capturePayment['details'][0]["issue"] == "INSTRUMENT_DECLINED"){
                  $customer_credits_packs->setCheckoutStatus($checkBuy->id_order,"card_declined");
                } else{
                  // Check capture status
                  if(isset($capturePayment['status']) && $capturePayment['status'] == "COMPLETED") {
                      processSitePayment($checkBuy);
                      $customer_credits_packs->setCheckoutStatus($checkBuy->id_order,"succeeded");
                  }
                }
            }
            break;

        default:
            break;
    }
}
http_response_code(200);
?>