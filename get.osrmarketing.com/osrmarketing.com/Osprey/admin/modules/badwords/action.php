<?php 
$module_name = "badwords";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");


$badwords_output = $_POST['badwords'];
$badwordsArray = preg_split('/\s*,\s*/', trim($badwords_output));
$badwordsArray = array_filter($badwordsArray);
$_POST['badwords'] = $badwords_output;


function handleAction($module_name, $action, $id = null) {
    global $config;
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';

    if($config->demo_mode){
        redirect("/admin/{$module_name}", "This option is not available in demo mode.", "error");
        exit();
    }    

    switch ($action) {
        case 'edit':
            $result = $module_object->update($id);
            $message = $result ? 'Record updated successfully.' : 'An error occurred while updating the record. Please try again.';
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