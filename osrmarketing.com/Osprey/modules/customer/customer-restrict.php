<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("../../inc/includes.php");

if(isset($_SESSION['id_customer'])){
    $checkUserData = $customers->get($_SESSION['id_customer']);
}

if (!isset($_SESSION['id_customer']) || empty($_SESSION['id_customer']) || !$checkUserData->id) {
    header("Location: /sign-in");
    die();
}