<?php 
$module_name = "sales";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");


function handleAction($module_name, $action, $id = null) {
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';

    switch ($action) {
        case 'aprove_payment':
            //header('Content-Type: application/json');
            $customer_credits_packs = new CustomerCreditsPacks();
            $getCustomerCreditsPacks = $customer_credits_packs->getByIdOrder($_POST['id_order']);

            if($getCustomerCreditsPacks){
                $credits_packs = new CreditsPacks();
                $getPackage = $credits_packs->get($getCustomerCreditsPacks->id_credit_pack);

                if($getPackage){
                    if($customer_credits_packs->checkoutSuccess($getCustomerCreditsPacks->id_customer, $getCustomerCreditsPacks->id_order,$getPackage->credit)){
                        $response = array('status' => 'updated credits');
                        if($customer_credits_packs->approvePayment($_POST['id_order'])){
                            $response = array('status' => 'success');
                        } else {
                            $response = array('status' => 'error approving payment');
                        }
                    }else{
                        $response = array('status' => 'Error updating credits');
                    }
                }else{
                    $response = array('status' => 'Credit pack not found');
                }

            }else{
                $response = array('status' => 'Payment not found');
            }

            echo json_encode($response);
            break;

    }

    if ($message) {
        $messageType = $result ? 'success' : 'error';
        redirect("/admin/{$module_name}", $message, $messageType);
    }
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($action) {
    handleAction($module_name, $action, $id);
}
?>
