<?php 
$module_name = "credits_packs";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");

function handlePromptCreditPacks($id) {
    $prompts_credits_packs = new PromptsCreditsPacks();
    $prompts_credits_packs->deleteCreditPack($id);


    if (isset($_POST['prompts_credits_packs']) && is_array($_POST['prompts_credits_packs'])) {
        foreach ($_POST['prompts_credits_packs'] as $showPromptsVip) {
            $prompts_credits_packs->addPromptCreditPack($showPromptsVip,$id);
        }
    }
}

if (isset($_POST['description'])) {
    $descriptionArray = $_POST['description'];
    $descriptionArray = array_filter($descriptionArray, function($value) {
        return !is_null($value) && $value !== '';
    });
    
    $_POST['description'] = json_encode($descriptionArray,  JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

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
            if($result){
                $lastInsertId = $module_object->getLastInsertId();
                handlePromptCreditPacks($lastInsertId);
            }
            $message = $result ? 'Record added successfully.' : 'An error occurred while adding a new record. Please try again.';
            break;
        case 'edit':
            handlePromptCreditPacks($_POST['id']); 
            $result = $module_object->update($id);
            $message = $result ? 'Record updated successfully.' : 'An error occurred while updating the record. Please try again.';
            break;
        case 'delete':
            $prompts_credits_packs = new PromptsCreditsPacks();
            $prompts_credits_packs->deleteCreditPack($id);
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
