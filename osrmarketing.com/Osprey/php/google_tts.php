<?php
require_once("../inc/includes.php");
header('Access-Control-Allow-Origin: ' . $base_url);
header('Content-Type: application/json');

$apiUrl = "https://texttospeech.googleapis.com/v1";
$apiKey = $config->google_cloud_text_to_speech_api_key; 

// Identificar a ação a ser tomada com base na solicitação
if (isset($_GET['action']) && $_GET['action'] === 'listVoices') {
    // Listar as vozes do Google TTS
    $voicesApiUrl = $apiUrl . "/voices?key=" . $apiKey;
    
    $ch = curl_init($voicesApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo json_encode(['error' => curl_error($ch)]);
        curl_close($ch);
        exit();
    }

    echo $response;
    curl_close($ch);
} else {
    // Ler o corpo da solicitação e decodificar o JSON em um array PHP
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar se os campos necessários estão presentes
    if (!isset($data['text'], $data['languageCode'], $data['name'], $data['ssmlGender'], $data['audioEncoding'])) {
        echo json_encode(['error' => 'Campos obrigatórios faltando']);
        exit();
    }

    $text = $data['text'];
    $languageCode = $data['languageCode'];
    $name = $data['name'];
    $ssmlGender = $data['ssmlGender'];
    $audioEncoding = $data['audioEncoding'];

    $payload = [
        "input" => ["text" => $text],
        "voice" => ["languageCode" => $languageCode, "name" => $name, "ssmlGender" => $ssmlGender],
        "audioConfig" => ["audioEncoding" => $audioEncoding]
    ];

    $synthesizeApiUrl = $apiUrl . "/text:synthesize?key=" . $apiKey;

    $ch = curl_init($synthesizeApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo json_encode(['error' => curl_error($ch)]);
        curl_close($ch);
        exit();
    }

    echo $response;
    curl_close($ch);
}