<?php 
$module_name = "customers";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");


function handleAction($module_name, $action, $id = null) {
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';

    switch ($action) {
        case 'login':
        $getCustomer = $customers->get($_REQUEST['id']);
        if($getCustomer){
            $_SESSION['id_customer'] = $getCustomer->id;
            $_SESSION['name_customer_name'] = $getCustomer->name;
            $_SESSION['email_customer'] = $getCustomer->email;
            redirect($base_url.'/panel', 'Login successful, welcome!', 'success');
        }else{
            redirect("/admin/{$module_name}/edit/{$_REQUEST['id']}", "User not found", "error");   
        }
        case 'edit':
            $checkCustomerEmail = $customers->get($_POST['id']);
            //checks if it's an email change attempt
            if($checkCustomerEmail->email != $_POST['email']){
                //Checks if the new email entered is not already in use
                $checkDuplicateEmail = $customers->getByEmail($_POST['email']);
                if($checkDuplicateEmail){
                    redirect("/admin/{$module_name}/edit/{$_POST['id']}", "The new email entered is already in use, try another one", "error");
                }
            }
            
            $result = $module_object->update($id);
            $message = $result ? 'Record updated successfully.' : 'An error occurred while updating the record. Please try again.';
            break;   

        case 'delete':
            $result = $module_object->delete($id);
            $message = $result ? 'Record deleted successfully.' : 'An error occurred while deleting the record. Please try again.';
            break;
    }

    if ($message) {
        $messageType = $result ? 'success' : 'error';
        redirect("/admin/{$module_name}", $message, $messageType, (isset($_POST['refer']) && $_POST['refer'] === 'ajax') ? true : false);
    }
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($action) {
    handleAction($module_name, $action, $id);
}
?>
