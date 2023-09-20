<?php 
$module_name = "posts";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");

function handleTags($id_post) {
    $posts_tags = new PostsTags();
    $posts_tags->deletepostTag($id_post);
    if (isset($_POST['tags']) && is_array($_POST['tags'])) {
        foreach ($_POST['tags'] as $id_tag) {
            $posts_tags->addPostTag($id_post, $id_tag);
        }
    }
}


function handleAction($module_name, $action, $id = null) {
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';

    switch ($action) {
        case 'add':
            //$module_object->debug(true);
            $result = $module_object->add();
            if($result){
                $lastInsertId = $module_object->getLastInsertId();
                handleTags($lastInsertId);                
            }
            $message = $result ? 'Record added successfully.' : 'An error occurred while adding a new record. Please try again.';
            break;
        case 'edit':
            handleTags($_POST['id']);
            $result = $module_object->update($id);
            $message = $result ? 'Record updated successfully.' : 'An error occurred while updating the record. Please try again.';
            break;
        case 'delete':
            $posts_tags = new PostsTags();
            $posts_tags->deletepostTag($id);
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