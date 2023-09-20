<?php 
$module_name = "theme";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
if($config->demo_mode){
    redirect("/admin/{$module_name}", "This option is not available in demo mode.", "error");
    exit();
}    

$theme_options = array();
$ignored_fields = array("id", "action");

foreach ($_POST as $key => $value) {
    if (!in_array($key, $ignored_fields)) {
        $theme_options[$key] = $value;
    }
}

// Converter o array "theme_options" em uma string JSON
$theme_options_json = json_encode($theme_options, JSON_HEX_APOS);
$_POST['theme_options'] = $theme_options_json;

// Processar os campos de upload
function processUpload($fieldName)
{
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] == 0) {
        $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . "/public_uploads/";
        $fileExtension = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
        $uniqueID = uniqid();
        $imageName = $uniqueID . "." . $fileExtension;
        $destinationPath = $uploadDirectory . $imageName;

        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destinationPath)) {
            return $imageName;
        } else {
            redirect('/admin/', 'Error moving file to destination folder, check folder permission.', 'error');
        }
    }
    return null;
}

// Processar os campos de upload específicos
$logoImage = processUpload('image_logo');
$heroBackgroundImage = processUpload('image_hero_background');
$heroImage = processUpload('image_hero');
$image_sign_in = processUpload('image_sign_in');
$image_sign_up = processUpload('image_sign_up');
$image_fpassword = processUpload('image_fpassword');
$image_fav = processUpload('image_fav');

// Adicionar os valores dos campos de upload diretamente no array $_POST
$_POST['image_logo'] = $logoImage;
$_POST['image_hero_background'] = $heroBackgroundImage;
$_POST['image_hero'] = $heroImage;
$_POST['image_sign_in'] = $image_sign_in;
$_POST['image_sign_up'] = $image_sign_up;
$_POST['image_fpassword'] = $image_fpassword;
$_POST['image_fav'] = $image_fav;

// Converter o array "theme_options" em uma string JSON
$theme_options_json = json_encode($theme_options, JSON_HEX_APOS);
$_POST['theme_options'] = $theme_options_json;


function handleAction($module_name, $action, $id = null) {
    global $$module_name;
    $module_object = $$module_name;
    $result = false;
    $message = '';

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