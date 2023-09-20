<?php
require_once("../inc/includes.php");
include('key.php');
$header = [
    "Authorization: Bearer " . $API_KEY,
    "Content-type: application/json",
];

$ai_id = "";
$prompt = "";
$model = "image-alpha-001";
$num_images = (int) $config->dalle_generated_img_count;
$size = $config->dalle_img_size;


if(!$isLogged){
    echo json_encode([
        "status" => 0,
        "message" => $lang['dalle_require_login'],
    ]);
    die();
}


// Read input data
$data = file_get_contents("php://input");
if (is_string($data)) {
    $data = json_decode($data, true);
    $prompt = $data["prompt"];
    $ai_id = $data["ai_id"];
}

$AI = $prompts->get($ai_id);
$ai_name = $AI->name;
$total_characters = $config->dalle_spend_credits;
$ai_id = $AI->id;


// Verifica se o array $_SESSION["history"][$ai_id] existe
if(!isset($_SESSION["history"][$ai_id]) || !is_array($_SESSION["history"][$ai_id])){
    $_SESSION["history"][$ai_id] = array();
}

$next_item_order = 0;  // Initialize to 0 as default
if (isset($_SESSION["history"][$ai_id])) {
    $next_item_order = count($_SESSION["history"][$ai_id]);
}


$next_item_order = count($_SESSION["history"][$ai_id]);
$_SESSION["history"][$ai_id][] = [
    "item_order" => $next_item_order,
    "id_message" => $id = md5(microtime()),
    "role" => "user",
    "content" => "/img ".$prompt,
    "datetime" => date("d/m/Y, H:i:s"),
    "saved" => false
];

$url = "https://api.openai.com/v1/images/generations";
$params = json_encode([
    "prompt" => $prompt,
    "model" => $model,
    "num_images" => $num_images,
    "size" => $size
]);

// Initialize cURL
$curl = curl_init($url);
$options = [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 2,
];
curl_setopt_array($curl, $options);
$response = curl_exec($curl);

if ($response === false) {
    echo json_encode([
        "status" => 0,
        "message" => "An error occurred: " . curl_error($curl),
    ]);
    die();
}

$httpcode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

if ($httpcode == 401) {
    $r = json_decode($response);
    echo json_encode([
        "status" => 0,
        "message" => $r->error->message,
    ]);
    die();
}
if ($httpcode == 200) {
    $json_array = json_decode($response, true);
    echo json_encode([
        "status" => 1,
        "message" => $json_array,
    ]);

    if($isLogged){
        // Rest of your code...

    // Check if 'data' key exists in the array
    if(isset($json_array['data'])){
        foreach($json_array['data'] as $key => $item) {
            // Check if 'url' key exists
            if(isset($item['url'])) {
                // Get image content from the url
                $imageContent = file_get_contents($item['url']);

                // Generate a unique id for the filename
                $filename = md5(uniqid(rand(), true)).'.png';

                // Define the path where the image will be saved
                $path = '../public_uploads/dalle/';

                // Check if the directory already exists
                if (!file_exists($path)) {
                    // Create the directory
                    mkdir($path, 0777, true);
                }

                // Save the image content to a file
                file_put_contents($path . $filename, $imageContent);

                // Replace the url in the array with the local filename
                $json_array['data'][$key]['url'] = $filename;
            }
        }
    }

        $next_item_order = count($_SESSION["history"][$ai_id]);
        // Save the modified array to $_SESSION["history"]
        $_SESSION["history"][$ai_id][] = [
            "item_order" => $next_item_order,
            "id_message" => $id = md5(microtime()),
            "role" => "assistant",
            "content" => $prompt,
            "dall_e_array" => json_encode($json_array),
            "name" => $ai_name,
            "datetime" => date("d/m/Y, H:i:s"),
            "total_characters" => $total_characters,
            "saved" => false
        ];
        
        // Subtract customer credit
        if ($userCredits > 0) {
            $customers->subtractCredits($_SESSION['id_customer'], $total_characters);
        }
    }
}else {
    $r = json_decode($response);
    echo json_encode([
        "status" => 0,
        "message" => "Error HTTP code " . $httpcode." - ".$r->error->message,
    ]);
}