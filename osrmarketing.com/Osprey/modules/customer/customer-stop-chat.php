<?php 
require_once("../../inc/includes.php");

if ($isLogged) {
    if (isset($_SESSION['id_customer']) && $_SESSION['id_customer']) {
        $checkUserData = $customers->get($_SESSION['id_customer']);
        if ($checkUserData) {

			$postData = file_get_contents('php://input');

			// Decodifica o JSON para obter os dados
			$data = json_decode($postData);

			// Verifica se a propriedade "characterCount" existe
			if (isset($data->characterCount)) {
			    $characterCount = $data->characterCount;

		        if($userCredits > 0){
		            $customers->subtractCredits($_SESSION['id_customer'],$characterCount);
		        }			    
			    $responseData = array(
			        'success' => true,
			        'message' => 'success'
			    );
			    echo json_encode($responseData);
			} else {
			    $responseData = array(
			        'success' => false,
			        'message' => 'Invalid data received.'
			    );
			    echo json_encode($responseData);
			}


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
    if (isset($_SESSION['message_count'])) {
        $_SESSION['message_count']++;
    }        
    unset($_SESSION["history"]);	
    $errorData = array(
        'error' => true,
        'message' => 'User is not logged in'
    );
    echo json_encode($errorData);
}