<?php 
$module_name = "menus";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
if($config->demo_mode){
    redirect("/admin/{$module_name}", "This option is not available in demo mode.", "error");
    exit();
}  

function handleAction($module_name, $action, $id = null) {
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';
    $demo_mode = true;

    switch ($action) {
        case 'add':
            $max_item_order = $module_object->getMaxOrder()->max_order;
            $_POST['item_order'] = $max_item_order+1;
                    
            //$module_object->debug(true);
            $result = $module_object->add();
            $message = $result ? 'Record added successfully.' : 'An error occurred while adding a new record. Please try again.';
            break;
        case 'edit':
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
        redirect("/admin/{$module_name}", $message, $messageType);
    }
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($action) {
    handleAction($module_name, $action, $id);
}
?>
