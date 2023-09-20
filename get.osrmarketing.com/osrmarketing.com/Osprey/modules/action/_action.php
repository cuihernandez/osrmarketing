<?php
if (isset($_POST['action'])) {
	require_once("../../inc/includes.php");
  require_once("../../vendor/autoload.php");


	// Escape single quotes and other special characters
	foreach ($_POST as $key => $value) {
	    $_POST[$key] = addslashes($value);
	}


	  //====Create Account====
    if ($_POST['action'] == "create-account") {

      if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['name'])) {
          redirect($base_url.'/sign-up', $lang['error_invalid_data'], 'error');
      } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          redirect($base_url.'/sign-up', $lang['invalid_email'], 'error');
      }

      if($config->use_recaptcha){
        $secretKey = $config->recaptcha_secret_key;
        $responseKey = $_POST['recaptcha_response'];
        $userIP = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
        $response = file_get_contents($url);
        $response = json_decode($response);
        if(!$response->success){
          redirect($base_url.'/sign-up', $lang['error_invalid_recaptcha'], 'error');
        }
      }      

      $_SESSION['form_name'] = $_POST['name'];
      $_SESSION['form_email'] = $_POST['email'];
      $_SESSION['form_password'] = $_POST['password'];
      $_POST['confirm_email_token'] = generateHash();

    	$_POST['email'] = strtolower($_POST['email']);
    	$checkEmail = $customers->getByEmail($_POST['email']);

      //Check duplicate email
    	if(isset($checkEmail->email) && $checkEmail->email){
 			  redirect($base_url.'/sign-up', $lang['error_duplicate_email'], 'error');
    	}
      
      //Encrypting password
      $_POST['password'] = md5($_POST['password'].addslashes($saltnumber));
      $_POST['credits'] = intval($config->credit_account_bonus);
      
      if($customers->add()){    
        unset($_SESSION['form_name']);
        unset($_SESSION['form_email']);
        unset($_SESSION['form_password']);

        $lastInsertId = $customers->getLastInsertId();
        $checkUserData = $customers->get($lastInsertId);

        $_SESSION['id_customer'] = $checkUserData->id;
        $_SESSION['name_customer_name'] = $checkUserData->name;
        $_SESSION['email_customer'] = $checkUserData->email;   


        if ($config->customer_confirm_email) {
            $smtp_fields = array(
                'subject' => $config->customer_confirm_email_subject,
                'email' => $checkUserData->email,
                'type' => 'confirm_email_customer',
                'email_customer_link' => $checkUserData->confirm_email_token,
                'recipient_name' => $checkUserData->name
            );
            $emailSent = sendEmail($smtp_fields);
            
            // Armazene o tempo atual na sessão
            $_SESSION['last_email_sent_time'] = time();
        }

        
        if (isset($_SESSION['threads'])) {
            unset($_SESSION['threads']);
        }
        if (isset($_GET['chat'])) {
            unset($_GET['chat']);
        }
 
          if(isset($_SESSION['buy_credit_id']) && !empty($_SESSION['buy_credit_id'])){
            redirect($base_url.'/recharge-credits/'.$_SESSION['buy_credit_id']."?payment_method=".$_SESSION['payment_method'], '', 'success');
          }else{
            redirect($base_url.'/panel', $lang['login_success_message'], 'success');
          }

        }else{
        	redirect($base_url.'/sign-up', $lang['error_sign_up'], 'error');
        }
    }

    //====Login Account====
    if ($_POST['action'] == "login-account") {

      if($config->use_recaptcha){
        $secretKey = $config->recaptcha_secret_key;
        $responseKey = $_POST['recaptcha_response'];
        $userIP = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
        $response = file_get_contents($url);
        $response = json_decode($response);
        if(!$response->success){
          redirect($base_url.'/sign-in', $lang['error_invalid_recaptcha'], 'error');
        }
      }

      $_SESSION['form_email'] = $_POST['email'];
      $_SESSION['form_password'] = $_POST['password'];
      $_POST['password'] = md5($_POST['password'].addslashes($saltnumber));
      $_POST['email'] = strtolower($_POST['email']);

      if (empty($_POST['email']) || empty($_POST['password'])) {
        redirect($base_url.'/sign-in', $lang['error_invalid_data'], 'error');
      }

      $checkUserData = $customers->checkUserData($_POST['email'],$_POST['password']);

      //Check duplicate email
      if($checkUserData->id){
        //login valid
        unset($_SESSION['form_email']);
        unset($_SESSION['form_password']);
        
        if (isset($_SESSION['threads'])) {
            unset($_SESSION['threads']);
        }
        if (isset($_GET['chat'])) {
            unset($_GET['chat']);
        }


        $_SESSION['id_customer'] = $checkUserData->id;
        $_SESSION['name_customer_name'] = $checkUserData->name;
        $_SESSION['email_customer'] = $checkUserData->email;

        if(isset($_SESSION['buy_credit_id']) && !empty($_SESSION['buy_credit_id'])){
          redirect($base_url.'/recharge-credits/'.$_SESSION['buy_credit_id']."?payment_method=".$_SESSION['payment_method'], '', 'success');
        }else{
          redirect($base_url.'/panel', $lang['login_success_message'], 'success');
        }
      }else{
        redirect($base_url.'/sign-in', $lang['error_invalid_data'], 'error');
      }
    }

    //====Update Account====
    if ($_POST['action'] == "update-account") {
      if(isset($_POST['email'])){
        unlink($_POST['email']);
      }

      if($_POST['password']){
        $_POST['password'] = md5($_POST['password'].addslashes($saltnumber));
      }else{
        unset($_POST['password']);
      }      

      //check customer
      $checkCustomer = $customers->getByEmail(addslashes($_SESSION['email_customer']));
      
      if($checkCustomer->id === $_SESSION['id_customer']){
        //$customers->debug(true);
        unset($_POST['id']);
        unset($_POST['created_at']);
        unset($_POST['email']);

        if($customers->update($_SESSION['id_customer'])){
          redirect($base_url.'/panel/my-account', $lang['data_updated_success'], 'success');
        }else{
          redirect($base_url.'/panel/my-account', $lang['error_update_record'], 'error');
        }

      }else{
        redirect($base_url.'/panel/my-account', $lang['error_update_record'], 'error');
      }
    }  

    //====Reset Password====
    if ($_POST['action'] == "reset-password") {
      $_POST['email'] = strtolower($_POST['email']);
      $reset_password_email = addslashes($_POST['email']);


      if (empty($_POST['email'])) {
        redirect($base_url.'/reset-password', $lang['invalid_email'], 'error');
      }

      
      if($config->use_recaptcha){
        $secretKey = $config->recaptcha_secret_key;
        $responseKey = $_POST['recaptcha_response'];
        $userIP = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
        $response = file_get_contents($url);
        $response = json_decode($response);
        if(!$response->success){
          redirect($base_url.'/reset-password', $lang['error_invalid_recaptcha'], 'error');
        }
      }    


      function generatePasswordCode() {
          $min = 10000000000; // Smallest possible
          $max = 99999999999; // Largest possible number
          $code = rand($min, $max); // Generates a random number between $min and $max
          return $code;
      }

      //check customer
      $checkCustomer = $customers->getByEmail($reset_password_email);

      if($checkCustomer->id){
      //Save code in database  
      $generatePasswordCode = generatePasswordCode();
      $customers->updatePasswordToken($checkCustomer->id,$generatePasswordCode);

      $smtp_fields = array(
          'subject' => $config->recovery_code_subject,
          'email' => $checkCustomer->email,
          'type' => 'reset_password_code',
          'reset_password_code' => $generatePasswordCode,
          'recipient_name' => $checkCustomer->name
      );

      $emailSent = sendEmail($smtp_fields);

      }else{
        unset($_POST['recovery_password_token']);
      }
      $_SESSION['recovery_email'] = $reset_password_email;
      redirect($base_url.'/reset-password?action=enter_code', "", 'success');
    }

    //====Reset Password Check Code====
    if ($_POST['action'] == "check-password-code") {
      if (!isset($_SESSION['recovery_email']) || empty($_SESSION['recovery_email'])) {
        redirect($base_url.'/reset-password', $lang['unable_recover_acess'], 'error');
      }
      
      $recovery_email = strtolower($_SESSION['recovery_email']);     
      $checkCustomer = $customers->getByEmail($recovery_email);
      if(isset($checkCustomer->id) && $checkCustomer->id){
          //Save code in database  
          if($checkCustomer->recovery_password_token === $_POST['recovery_password_token']){
            
            $_SESSION['id_customer'] = $checkCustomer->id;
            $_SESSION['name_customer_name'] = $checkCustomer->name;
            $_SESSION['email_customer'] = $checkCustomer->email;            
            redirect($base_url.'/panel/my-account', $lang['welcome_back_password_recovery'], 'success');
          }else{
            redirect($base_url.'/reset-password?action=enter_code', $lang['incorrect_code'], 'error');
          }
      }else{
        redirect($base_url.'/reset-password', $lang['unable_recover_acess'], 'error');
      }
      
    }

}else{
  die($lang['invalid_data']);
}