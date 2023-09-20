<?php 
$module_name = "pages";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");

function handleAction($module_name, $action, $id = null) {
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';   

    switch ($action) {
        case 'add':
            $max_item_order = $module_object->getMaxOrder()->max_order;
            $_POST['item_order'] = $max_item_order+1;
                    
            $result = $module_object->add();
            $message = $result ? 'Record added successfully.' : 'An error occurred while adding a new record. Please try again.';
            break;
        case 'edit':
//            $module_object->debug(true);
            $result = $module_object->update($id);
            $message = $result ? 'Record updated successfully.' : 'An error occurred while updating the record. Please try again.';
            break;
        case 'delete':
            $checkDelete = $module_object->get($id);
            if($checkDelete->forbid_delete){
                redirect("/admin/{$module_name}", "This item cannot be deleted", "error");        
            }
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
