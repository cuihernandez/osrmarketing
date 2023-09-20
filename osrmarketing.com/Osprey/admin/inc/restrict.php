<?php
require_once("includes.php");

// Check user agent
if(isset($config->admin_check_user_agent) && $config->admin_check_user_agent){
    if (!isset($_SESSION['user_agent']) || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_unset();
        session_destroy();
        header("location:/admin/login?error=user_agent_changed");
        die();
    }
}

// Check client IP
if(isset($config->admin_check_ip_address) && $config->admin_check_ip_address){
    if (!isset($_SESSION['ip_address']) || $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
        session_unset();
        session_destroy();
        header("location:/admin/login?error=ip_changed");
        die();
    }
}

// Regenerate session id after login
if(isset($config->admin_check_token) && $config->admin_check_token){
    if (!isset($_SESSION['regenerated_id'])) {
        session_regenerate_id(true);
        $_SESSION['regenerated_id'] = true;
    }

    // Check if the user exists and if the session token matches the database token
    if(!isset($getUser) || !$getUser || (!isset($_SESSION['token']) || $_SESSION['token'] !== $getUser->token)) {
        unset($_SESSION['token']);
        header("location:/admin/login?error=invalid_token");
        die();    
    }
}

if(!isset($_SESSION['admin_id'])) {
    header("location:/admin/login?error=invalid_data");
    die();    
}