<?php
    require_once("../../inc/includes.php");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION["id_customer"]);
    header("Location:".$base_url."/");
    exit;
?>