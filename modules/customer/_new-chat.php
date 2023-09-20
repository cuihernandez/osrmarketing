<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("../../inc/includes.php");
$AI = $prompts->getBySlug($_REQUEST['slug']);
if($AI->id){
    
    if (isset($_SESSION['history'][$AI->id])) {
        unset($_SESSION['history'][$AI->id]);
    }
    if (isset($_SESSION['threads'][$AI->id])) {
        unset($_SESSION['threads'][$AI->id]);
    }
    if (isset($_GET['chat'])) {
        unset($_GET['chat']);
    }

    $redirect_url = $base_url . "/chat/" . $AI->slug . (isset($_GET['embed']) ? "?embed_chat=true" : "");
    header("Location: " . $redirect_url);
    die();    
}else{
    header("location:".$base_url."/");
    die();
}