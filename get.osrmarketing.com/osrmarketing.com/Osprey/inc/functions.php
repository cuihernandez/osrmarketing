<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function redirect($url, $message = '', $status = 'success') {
    $_SESSION['action'] = $status;
    $_SESSION['action_message'] = $message;
    header("location: $url");
    exit();
}

// Function to decode Unicode escape sequences.
function decodeUnicodeEscapeSequences($matches) {
    return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
}

// Function to process translations.
// This replaces Unicode escape sequences with their corresponding UTF-8 characters.
function processTranslations($translations) {
    foreach ($translations as $key => $value) {
        $translations[$key] = preg_replace_callback('/u([0-9a-fA-F]{4})/', 'decodeUnicodeEscapeSequences', $value);
    }
    return $translations;
}

// Function to merge two arrays, while checking for empty values.
// Values from the second array will overwrite those from the first,
// unless they are empty or the corresponding value in the first array doesn't exist.
function array_merge_check_empty($arr1, $arr2) {
    foreach ($arr2 as $key => $value) {
        if (is_array($value) && isset($arr1[$key])) {
            $arr1[$key] = array_merge_check_empty($arr1[$key], $arr2[$key]);
        } else {
            if (empty($value)) {
                continue;
            }
            $arr1[$key] = $value;
        }
    }
    return $arr1;
}

// Function to return the URL protocol (http or https) based on the $_SERVER superglobal.
function url() {
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

// Function to remove custom input from a given text.
// This removes any text starting with '↵↵' and ending with a period (inclusive).
function wrapCodeInPreTag($text) {
    // Flag to check if any code block has been detected
    $isCodeDetected = false;
    
    // Find code blocks between ``` delimiters and wrap them with <pre><code>
    $pattern = '/```(\w+)?([\s\S]*?)```/s';
    $updatedText = preg_replace_callback($pattern, function ($matches) use (&$isCodeDetected) {
        global $lang;
        // Set the flag to true as we found a code block
        $isCodeDetected = true;

        $copy_label = $lang['copy_code1'];
        $code = htmlspecialchars($matches[2], ENT_QUOTES, 'UTF-8');
        $copyButton = '<button class="copy-code" onclick="copyCode(this)">
            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
            </svg> <span class="label-copy-code">'.$copy_label.'</span>
        </button>';

        return '<pre><code>' . $code . '</code>' . $copyButton . '</pre>';
    }, $text);

    // If the flag is true, append the JavaScript to the text
    if ($isCodeDetected) {
        $updatedText .= '<script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof hljs !== "undefined") {
                    hljs.highlightAll();
                }
            });
        </script>';
    }

    return $updatedText;
}

function removeCustomInput($text) {
    // Procura a posição da primeira ocorrência de "↵↵"
    $pos = strpos($text, '↵↵');

    // Se a ocorrência for encontrada, remove tudo a partir dela
    if ($pos !== false) {
        $clean_text = substr($text, 0, $pos);
    } else {
        // Se não houver ocorrência de "↵↵", retorna o texto original
        $clean_text = $text;
    }

    // Envolva o código em tags <pre>
    $clean_text = wrapCodeInPreTag($clean_text);

    return $clean_text;
}



function markdownParaHtml($texto) {
    $texto = nl2br($texto);  // Convert line breaks to <br/>
    $texto = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $texto);  // **text** to <strong>text</strong>
    $texto = preg_replace('/# (.*?)<br \/>/', '<h1>$1</h1>', $texto);  // # text to <h1>text</h1>
    $texto = preg_replace('/## (.*?)<br \/>/', '<h2>$1</h2>', $texto);  // ## text to <h2>text</h2>
    $texto = preg_replace('/### (.*?)<br \/>/', '<h3>$1</h3>', $texto);  // ### text to <h3>text</h3>
    return $texto;
}

// Function to reorder an array by a given id.
// This moves the item with the specified id to the start of the array.
function reorderArrayById($array, $id) {
    $reorderedArray = [];
    $itemWithId = null;

    foreach ($array as $item) {
        if ($item->id == $id) {
            $itemWithId = $item;
        } else {
            $reorderedArray[] = $item;
        }
    }

    if ($itemWithId) {
        array_unshift($reorderedArray, $itemWithId);
    }

    return $reorderedArray;
}

// Function to generate a new id for a thread.
function threadNewID() {
    return uniqid("thread_", true);
}

function displayError($error){
    echo "<div class='alert alert-danger'>".$error."</div>";
}

function findPaymentIntent($config, $id_order) {

    try {
        // List all PaymentIntents
        $paymentIntents = \Stripe\PaymentIntent::all();

        // Find the PaymentIntent with the matching id_order
        $paymentIntentFound = null;
        foreach ($paymentIntents->data as $paymentIntent) {
            if (isset($paymentIntent->metadata['id_order']) && $paymentIntent->metadata['id_order'] === $id_order) {
                $paymentIntentFound = $paymentIntent;
                break;
            }
        }
        
        return $paymentIntentFound;

    } catch (\Stripe\Exception\AuthenticationException $e) {
        // Error handling
        displayError($e->getMessage());
        die(); // You can also use 'exit' here
    } catch (\Exception $e) {
        displayError($e->getMessage());
        die(); // You can also use 'exit' here
    }
}

// Function to truncate text to a specified maximum length.
// This cuts off the text at the last full word before the limit, and appends an ellipsis if the text was truncated.
// Line breaks are then converted to HTML <br> tags.
function truncateText($text, $maxLength) {
    if (strlen($text) > $maxLength) {
        $text = substr($text, 0, strrpos(substr($text, 0, $maxLength), ' '));
        $text .= '...';
    }
    $text = nl2br($text);
    return $text;
}

function formatDate($date, $includeTime = false) {
    // Bring the variable into the function scope
    global $getDefaultLanguage;

    // Define the language
    $lang = $getDefaultLanguage->lang;
    // Create a DateTime object from the input date
    $dateTime = new DateTime($date);

    // Format the date
    if ($includeTime) {
        // create a DateTimeFormatter
        $formatter = new IntlDateFormatter($lang,IntlDateFormatter::LONG,IntlDateFormatter::LONG);
        $formattedDate = $formatter->format($dateTime);
    } else {
        $formatter = new IntlDateFormatter($lang,IntlDateFormatter::LONG,IntlDateFormatter::NONE);
        $formattedDate = $formatter->format($dateTime);
    }

    return $formattedDate;
}

function createSitemapEntry($url, $lastmod = null, $priority = 0.5) {
    $sitemapEntry = "<url>\n";
    $sitemapEntry .= "\t<loc>{$url}</loc>\n";
    
    if ($lastmod) {
        $sitemapEntry .= "\t<lastmod>{$lastmod}</lastmod>\n";
    }

    $sitemapEntry .= "\t<priority>{$priority}</priority>\n";
    $sitemapEntry .= "</url>\n";

    return $sitemapEntry;
}

$user_credit_pack = array();
function getCustomerCreditPack($customerId) {
    global $customer_credits_packs, $credits_packs;
    $user_credit_pack = array();
    $getCreditPackCustomer = $customer_credits_packs->getByIdSucceededCustomer($customerId);

    foreach ($getCreditPackCustomer as $showCreditPackCustomer) {
        if($showCreditPackCustomer->id){
            $getTierCreditPack = $credits_packs->get($showCreditPackCustomer->id_credit_pack);
            if($getTierCreditPack !== false && $getTierCreditPack !== null) {
                $user_credit_pack[] = array(
                    'tier' => $getTierCreditPack->tier,
                    'id_credits_pack' => $showCreditPackCustomer->id_credit_pack
                );
            } 
        }
    }
    return $user_credit_pack;
}

function checkLoggedOutVipStatus($idPrompt, $prompts){
    $checkVipByIdPrompt = $prompts->checkVipByIdPrompt($idPrompt)->Fetch();
    return isset($checkVipByIdPrompt->id) && $checkVipByIdPrompt->id;
}

function checkLoggedInVipStatus($idPrompt, $prompts, $credits_packs, $user_credit_pack){
    global $config;
    $required_credit_pack = getRequiredCreditPack($idPrompt, $prompts, $credits_packs);

    if (empty($required_credit_pack)) {
        return false;
    }

    $required_tiers = array_column($required_credit_pack, 'tier');
    foreach($required_tiers as $required_tier) {
        foreach($user_credit_pack as $user_pack) {
            if(($config->vip_higher_tier == 1 ? $user_pack['tier'] >= $required_tier : $user_pack['tier'] == $required_tier)) {
                return false;
            }
        }
    }

    return true;
}

function getRequiredCreditPack($idPrompt, $prompts, $credits_packs){
    $required_credit_pack = array();
    $getVipByIdPrompt = $prompts->checkVipByIdPrompt($idPrompt);

    foreach ($getVipByIdPrompt as $showVipByIdPrompt) {
        $getTierCreditPack = $credits_packs->get($showVipByIdPrompt->id_credits_pack);
        if(is_object($getTierCreditPack) && property_exists($getTierCreditPack, 'status')){
            if($getTierCreditPack->status){
                $required_credit_pack[] = array(
                    'tier' => $getTierCreditPack->tier,
                    'id_credits_pack' => $showVipByIdPrompt->id_credits_pack
                );
            }
        }
    }

    return $required_credit_pack;
}

function getPaypalToken() {
    try {
        global $config;
        $paypal_token = "";

        if($config->paypal_test_mode){
            $clientId = $config->paypal_clientid_test;
            $clientSecret = $config->paypal_secret_test;
            $url = 'https://api-m.sandbox.paypal.com/v1/oauth2/token';
        }else{
            $clientId = $config->paypal_clientid_production;
            $clientSecret = $config->paypal_secret_production;        
            $url = 'https://api-m.paypal.com/v1/oauth2/token';
        }

        $credentials = base64_encode($clientId . ":" . $clientSecret);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        // Close the cURL resource
        curl_close($ch);
        
        $jsonResponse = json_decode($response);
        if(isset($jsonResponse->error) && $jsonResponse->error){
            throw new Exception($jsonResponse->error);
        }else{
            $paypal_token = $jsonResponse->access_token;
        }
        
        return $paypal_token;
    } catch (\Exception $e) {
        displayError($e->getMessage());
        die();
    }
}



function createOrder($config, $md5_hash, $checkCreditPack,$base_url) {
    try {
        // Get the access token
        $accessToken = "";
        $accessTokenData = getPaypalToken();
    
        if (isset($accessTokenData['error'])) {
            // Handle the error
            throw new Exception($accessTokenData['error']);
        }else{
            $accessToken = $accessTokenData;
        }

        // Configure CURL and the payload
        $ch = curl_init();
        $payload = json_encode(
            array(
                "intent" => "CAPTURE",
                "purchase_units" => array(
                    array(
                        "reference_id" => "client_" . $_POST['id_customer'],
                        "amount" => array(
                            "currency_code" => $checkCreditPack->currency_code,
                            "value" => $checkCreditPack->amount / 100
                        ),
                        "description" =>  $checkCreditPack->name,
                    )
                ),
                "payment_source" => array(
                    "paypal" => array(
                        "experience_context" => array(
                            "payment_method_preference" => "IMMEDIATE_PAYMENT_REQUIRED",
                            "payment_method_selected" => "PAYPAL",
                            "landing_page" => "NO_PREFERENCE",
                            "shipping_preference" => "NO_SHIPPING",
                            "user_action" => "PAY_NOW",
                            "return_url" => $base_url . "/panel/checkout-complete?id_order=" . $md5_hash,
                            "cancel_url" => $base_url . "/panel/checkout-complete?id_order=" . $md5_hash
                        )
                    )
                )
            )
        );

        // Configure CURL options
        $url = $config->paypal_test_mode ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders' : 'https://api-m.paypal.com/v2/checkout/orders';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'PayPal-Request-Id:' . $md5_hash;
        $headers[] = 'Authorization: Bearer ' . $accessToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute CURL
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Error:' . curl_error($ch));
        }
        curl_close($ch);

        // Handle the CURL result
        $response = json_decode($result, true);

        if (isset($response['error'])) {
            throw new Exception($response['error']);
        }
        
        return $response;
    } catch (\Exception $e) {
        displayError($e->getMessage());
        die();
    }
}


function processPayment($orderToken) {
    try {
        global $config;
        $ch = curl_init();

        // Get the access token
        $accessTokenData = getPaypalToken();
        if (isset($accessTokenData['error'])) {
            // Handle the error
            throw new Exception($accessTokenData['error']);
        }else{
            $accessToken = $accessTokenData;
        }

        // Construir a URL dependendo do modo (test ou live)
        $url = $config->paypal_test_mode ? "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderToken" : "https://api-m.paypal.com/v2/checkout/orders/$orderToken";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

        // Configura os cabeçalhos da requisição
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $accessToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Executa a requisição
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Error:' . curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($result, true);

        if (isset($response['error'])) {
            throw new Exception($response['error']);
        }

        return $response;
    } catch (\Exception $e) {
        displayError($e->getMessage());
        die(); 
    }
}

function capturePayment($orderToken) {
    try {
        global $config;
        $ch = curl_init();

        // Get the access token
        $accessTokenData = getPaypalToken();
        if (isset($accessTokenData['error'])) {
            // Handle the error
            throw new Exception($accessTokenData['error']);
        } else {
            $accessToken = $accessTokenData;
        }

        // Construir a URL dependendo do modo (test ou live)
        $url = $config->paypal_test_mode ? "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderToken/capture" : "https://api-m.paypal.com/v2/checkout/orders/$orderToken/capture";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        // Configura os cabeçalhos da requisição
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $accessToken;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Executa a requisição
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Error:' . curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($result, true);

        if (isset($response['error'])) {
            throw new Exception($response['error']);
        }

        return $response;
    } catch (\Exception $e) {
        displayError($e->getMessage());
        die();
    }
}
function processSitePayment($checkPaymentOrigin) {
    try {
        global $customer_credits_packs;
        
        if((int) $checkPaymentOrigin->claimed == 0) {
            $customer_credits_packs->checkoutSuccess(
                $checkPaymentOrigin->id_customer, 
                $checkPaymentOrigin->id_order, 
                $checkPaymentOrigin->credit_amount
            );
        }
    } catch (\Exception $e) {
        displayError($e->getMessage());
        die(); 
    }
}

function generateHash() {
    $timestamp = microtime();
    $hash = md5($timestamp);
    return $hash;
}

function checkCustomerConfirmEmail($isLogged){
    global $customers;
    global $config;
    if ($isLogged) {
        $getCustomer = $customers->getCustomer($_SESSION['id_customer']);
        if ($config->customer_confirm_email && !empty($getCustomer->confirm_email_token)) {
            header("location:/panel");
            die();
        }
    }
}

function sendEmail($fields) {
  global $base_url;
  global $config;
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->SMTPDebug = 0; //Debug
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => $config->smtp_verify_peer == 1 ? true : false,
            'verify_peer_name' => $config->smtp_verify_peer_name == 1 ? true : false,
            'allow_self_signed' => $config->smtp_allow_self_signed == 1 ? true : false
            )
        );      
        if (!empty($config->smtp_secure)) {
            $mail->SMTPSecure = $config->smtp_secure;
        }        
        $mail->CharSet = $config->smtp_charset;  
        $mail->isSMTP();
        $mail->Host = $config->smtp_host;
        $mail->SMTPAuth = ($config->smtp_auth == 1) ? true : false;
        $mail->Username = $config->smtp_username; 
        $mail->Password = $config->smtp_password;
        $mail->Port = $config->smtp_port;

        // Remetente e destinatário
        $mail->setFrom($config->smtp_from, $config->smtp_from_name); 
        $mail->addAddress($fields['email'], $fields['recipient_name']);

        // Conteúdo do e-mail
        $mail->isHTML(true);

        $mail->Subject = $fields['subject'];
        if($fields['type'] == 'reset_password_code'){
          $mail->Body = str_replace('{{code}}', $fields['reset_password_code'], $config->email_template_recovery_code);
        }

        else if ($fields['type'] == 'confirm_email_customer') {
            if($fields['email_customer_link']){
                $link = $base_url."/panel?confirm=".$fields['email_customer_link'];
                
                $clickableLink = '<a href="'.$link.'" target="_blank">'.$link.'</a>';
                $content = $config->customer_confirm_email_content;
                $content = str_replace("{{link}}", $clickableLink, $content);
                $content = str_replace("{{name}}", $fields['recipient_name'], $content);
                $mail->Body = $content;
            }else{
                exit();
            }
        }    

        
        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}