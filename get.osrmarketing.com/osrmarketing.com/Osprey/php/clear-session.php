<?php
if ($_POST) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if ($_POST['action'] == "all") {
        $_SESSION["history"] = [];
        unset($_SESSION['threads']);
    }
    if ($_POST['action'] == "current") {
        unset($_SESSION["history"][$_POST['id']]);
        unset($_SESSION['threads'][$_POST['id']]);
        if (isset($_GET['chat'])) {
            unset($_GET['chat']);
        }
    }
}
?>