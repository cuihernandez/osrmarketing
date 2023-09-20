<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("inc/includes.php");

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header("location:/admin/login?error=missing_data");
    die();
}

$email = addslashes($_POST['email']);
$password = md5($_POST['password'].addslashes($saltnumber));

// Validate email
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

if (!$email) {
    header("location:/admin/login?error=invalid_email");
    die();
}

$get = $users->getData($email,$password);

if(isset($get->id)){
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $users->updateToken($get->id,$_SESSION['token']);
    $_SESSION['admin_id'] = $get->id;
    $_SESSION['admin_name'] = $get->name;
    $_SESSION['admin_email'] = $get->email;
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
    header("location:/admin/");
    die();
}else{
    header("location:/admin/login?error=invalid_data&email=" . urlencode($_POST['email']));
    die();
}
