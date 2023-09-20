<?php
header('X-Accel-Buffering: no');
ini_set('output_buffering', 'off');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once("../inc/includes.php");
include('key.php');

$config = $settings->get(1);
$allowed_origin = $base_url;
$total_characters = 0;

if($config->demo_mode){
    if($isLogged){
        $checkCustomer = $customers->getCustomerMessagesInfo($getCustomer->id);
        if($checkCustomer->total_messages > 10){
            echo 'data: {"error": "[DEMO_MODE]"}' . PHP_EOL;
            die();            
        }
    }
}


function check_credits($isLogged, $userCredits, $config) {
    global $prompts;
    $checkEmbed = $prompts->get($_POST['ai_id']);

    if(!$isLogged){
        if (isset($_SESSION['message_count']) && $_SESSION['message_count'] > $config->free_number_chats) {
            if(!$checkEmbed->allow_embed_chat){
                if(!$config->free_mode){
                    echo 'data: {"error": "[CHAT_LIMIT]"}' . PHP_EOL;
                    die();
                }
            }
        }            
    } else {
        //credits are over
        if($userCredits <= 0){
            if(!$checkEmbed->allow_embed_chat){
                if(!$config->free_mode){            
                    echo 'data: {"error": "[NO_CREDIT]"}' . PHP_EOL;
                    die();
                }
            }            
        }    
    }
}

function remove_duplicate_messages($messages) {
    $temp_array = array();
    $unique_messages = array();
    
    foreach ($messages as $key => $message) {
        $role = $message['role'];
        $content = $message['content'];
        
        $keyString = $role . $content;
        
        if (!isset($temp_array[$keyString])) {
            $temp_array[$keyString] = true;
            $unique_messages[] = $message;
        }
    }
    
    return $unique_messages;
}

function createParams($isGPT, $ai_name, $chat_messages, $model, $temperature, $frequency_penalty, $presence_penalty) {
    global $config;
        return [
            "messages" => $chat_messages,
            "model" => $model,
            "temperature" => $temperature,
            "max_tokens" => (int)$config->max_tokens_gpt,
            "frequency_penalty" => $frequency_penalty,
            "presence_penalty" => $presence_penalty,
            "stream" => true
        ];
}

check_credits($isLogged, @$userCredits, $config);

$ai_id = $model = $ai_name = $ai_welcome_message = $ai_prompt = "";
$user_prompt = "";

if (isset($_POST['ai_id'])) {
    $AI = $prompts->get($_POST['ai_id']);
    $ai_id = $AI->id;
    $model = $AI->API_MODEL;
    $ai_name = $AI->name;
    $ai_welcome_message = $AI->welcome_message;
    $ai_prompt = $AI->prompt;
}

if (isset($_POST['prompt'])) {
    $user_prompt = $_POST['prompt'];
}

$temperature = (isset($AI->temperature) ? (int)$AI->temperature : 1);
$frequency_penalty = (isset($AI->frequency_penalty) ? (int)$AI->frequency_penalty : 0);
$presence_penalty = (isset($AI->presence_penalty) ? (int)$AI->presence_penalty : 0);
$chunk_buffer = "";

if ($user_prompt == "") {
    echo 'data: {"error": "[ERROR]","message":"Message field cannot be empty"}' . PHP_EOL;
    die();
}


if (!isset($_SESSION["history"][$ai_id])) {
    $_SESSION["history"][$ai_id] = [
        [
            "item_order" => 0,
            "id_message" => $id = md5(microtime()),
            "role" => "system",
            "content" => $ai_prompt,
            "datetime" => date("d/m/Y, H:i:s"),
            "saved" => false
        ]
    ];

    if (isset($ai_welcome_message) && !empty($ai_welcome_message)) {
        $_SESSION["history"][$ai_id][] = [
            "item_order" => 1,
            "id_message" => $id = md5(microtime()),
            "role" => "assistant",
            "content" => $ai_welcome_message,
            "name" => $ai_name,
            "datetime" => date("d/m/Y, H:i:s"),
            "saved" => false
        ];
    }
}


$next_item_order = count($_SESSION["history"][$ai_id]);
$_SESSION["history"][$ai_id][] = [
    "item_order" => $next_item_order,
    "id_message" => $id = md5(microtime()),
    "role" => "user",
    "content" => $user_prompt,
    "datetime" => date("d/m/Y, H:i:s"),
    "saved" => false
];


$chat_messages = $_SESSION["history"][$ai_id];

$chat_messages_head = [
    [
        'role' => 'system',
        'content' => $ai_prompt
    ]
];

$max_length = $AI->array_message_limit_length;
$chat_messages_tail = array_slice($chat_messages, -$AI->array_message_history, $AI->array_message_history);
$chat_messages = array_merge($chat_messages_head, $chat_messages_tail);


$chat_messages = remove_duplicate_messages($chat_messages);
$chat_messages = array_map(function ($message) use ($max_length) {
    if ($message["role"] == 'user' || $message["role"] == 'assistant') {
        return [
            "role" => $message["role"],
            "content" => mb_strimwidth($message["content"], 0, $max_length, '...')
        ];
    }
    return $message;
}, $chat_messages);


$header = [
    "Authorization: Bearer " . $API_KEY,
    "Content-type: application/json",
];



$isGPT = strpos($model, "gpt") !== false;
$url = $isGPT ? "https://api.openai.com/v1/chat/completions" : "https://api.openai.com/v1/engines/$model/completions";
$options = JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT;
$params = json_encode(createParams($isGPT, $ai_name, $chat_messages, $model, $temperature, $frequency_penalty, $presence_penalty), $options);

$chunk_buffer = '';
$curl = curl_init($url);
$options = [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_WRITEFUNCTION => function ($curl, $data) use (&$chunk_buffer) {
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            $r = json_decode($data);
            echo 'data: {"error": "[ERROR]","message":"'.$r->error->code."  ".$r->error->message.'"}' . PHP_EOL;
        } else {
            $chunk_buffer .= $data;
            echo $data;
            ob_flush();
            flush();
            return strlen($data);
        }
    },
];

curl_setopt_array($curl, $options);
$response = curl_exec($curl);

if ($response === false) {
    //echo 'data: {"error": "[ERROR]","message":"' . curl_error($curl) . '"}' . PHP_EOL;
} else {
    if($isLogged){
        $chunk_buffer = str_replace("data: [DONE]", "", $chunk_buffer);
        $lines = explode("\n", $chunk_buffer);
        $assistant_response = "";
        $total_characters = 0;

        foreach ($lines as $line) {
            if (!empty(trim($line))) {
                $response_data = json_decode(trim(substr($line, 5)), true);
                if (isset($response_data["choices"][0]["delta"]["content"])) {
                    $total_characters += mb_strlen($response_data["choices"][0]["delta"]["content"]);
                    $assistant_response .= $response_data["choices"][0]["delta"]["content"];
                } elseif (isset($response_data["choices"][0]["text"])) { 
                    $total_characters += mb_strlen($response_data["choices"][0]["text"]);
                    $assistant_response .= $response_data["choices"][0]["text"];
                }
            }
        }

        $_SESSION["history"][$ai_id][] = [
            "item_order" => $next_item_order,
            "id_message" => $id = md5(microtime()),
            "role" => "assistant",
            "content" => $assistant_response,
            "name" => $ai_name,
            "datetime" => date("d/m/Y, H:i:s"),
            "total_characters" => $total_characters,
            "saved" => false
        ];
        if(!$config->free_mode){
            //Subtract customer credit
            if($userCredits > 0){
                $customers->subtractCredits($_SESSION['id_customer'],$total_characters);
            }
        }
    }else{
        if (isset($_SESSION['message_count'])) {
            $_SESSION['message_count']++;
        }        
        unset($_SESSION["history"]);
    }
}
?>