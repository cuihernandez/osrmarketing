<?php
require_once(__DIR__ . "/../../inc/restrict.php");
require_once(__DIR__ . "/../../inc/includes.php");

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if($config->demo_mode){
    echo json_encode(array('error' => 'demo_mode', 'message' => 'This option is not available in demo mode.'));
    exit();
}

function updateStatus($module, $status, $id)
{
    global $update;
    return $update->updateStatus($module, $status, $id);
}

function updateOrder($module, $ids, $item_orders)
{
    global $update;
    $response = array();

    for ($i = 0; $i < count($ids); $i++) {
        $id = $ids[$i];
        $item_order = $item_orders[$i];

        if ($update->updateOrder($module, $item_order, $id)) {
            $response[] = array('success' => true, 'message' => "Item " . ($i + 1) . " successfully updated order");
        } else {
            $response[] = array('success' => false, 'message' => "Error updating item " . ($i + 1) . " order");
        }
    }

    return $response;
}

if ($action == "update_status") {
    if($_POST['myModule'] == "languages"){
        $response = $update->updateDefaultLanguage($_POST['myModule'], $id);
        echo json_encode(array('success' => $response, 'message' => $response ? "Default language updated successfully" : "Language updating error"));
    }else{
        $response = updateStatus($_POST['myModule'], $_POST['status'], $id);
        echo json_encode(array('success' => $response, 'message' => $response ? "The item's status has been updated successfully" : "Error updating status"));
    }
}elseif ($action == "update_order") {
    $response = updateOrder($_POST['myModule'], $_POST['id'], $_POST['item_order']);
    echo json_encode(array('success' => $response, 'message' => $response ? "Order updated successfully" : "Error updating order"));
}
?>