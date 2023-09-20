<?php 
$module_name = "settings";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");


function handleAction($module_name, $action, $id = null) {
    global $config;
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';


    switch ($action) {
        case 'edit':
            //$module_object->debug(true);
            $result = $module_object->update($id);
            $message = $result ? 'Record updated successfully.' : 'An error occurred while updating the record. Please try again.';
            break;
    }

    if ($message) {
        $messageType = $result ? 'success' : 'error';
        redirect("/admin/{$module_name}"."#".$_POST['url_hash'], $message, $messageType);
    }
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($action) {
    handleAction($module_name, $action, $id);
}
?>