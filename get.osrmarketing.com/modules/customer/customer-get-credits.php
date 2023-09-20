<?php 
require_once("../../inc/includes.php");

if ($isLogged) {
    if (isset($_SESSION['id_customer']) && $_SESSION['id_customer']) {
        $checkUserData = $customers->get($_SESSION['id_customer']);
        if ($checkUserData) {
            $userData = array(
                'credits' => number_format($checkUserData->credits, 0, ',', '.')
            );
            echo json_encode($userData);
        } else {
            $errorData = array(
                'error' => true,
                'message' => 'User not found'
            );
            echo json_encode($errorData);
        }
    } else {
        $errorData = array(
            'error' => true,
            'message' => 'Invalid Customer ID'
        );
        echo json_encode($errorData);
    }
} else {
    $errorData = array(
        'error' => true,
        'message' => 'User is not logged in'
    );
    echo json_encode($errorData);
}