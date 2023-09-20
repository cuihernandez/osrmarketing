<?php 
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");

    // Get JSON input
    $json = file_get_contents('php://input');
    
    // Decode JSON
    $data = json_decode($json, true);

    // Sanitize and validate data
    $id_prompt = filter_var($data['id_prompt'], FILTER_VALIDATE_INT);
    $summary = filter_var($data['summary'], FILTER_SANITIZE_STRING);
    $audience = filter_var($data['audience'], FILTER_SANITIZE_STRING);
    $keywords = filter_var($data['keywords'], FILTER_SANITIZE_STRING);
    $minParagraphs = filter_var($data['minParagraphs'], FILTER_VALIDATE_INT);
    $maxParagraphs = filter_var($data['maxParagraphs'], FILTER_VALIDATE_INT);
    $writingStyle = filter_var($data['writingStyle'], FILTER_VALIDATE_INT);
    $textTone = filter_var($data['textTone'], FILTER_VALIDATE_INT);
    $language = filter_var($data['language'], FILTER_VALIDATE_INT);

    if($language){
        $getPromptsOutput = $prompts_output->get($language);
        $language = $getPromptsOutput->name;
    }

    if($textTone){
        $getPromptsTone = $prompts_tone->get($textTone);
        $textTone = $getPromptsTone->name;
    }

    if($writingStyle){
        $getPromptsWriting = $prompts_writing->get($writingStyle);
        $writingStyle = $getPromptsWriting->name;
    }

    $text = "Create a complete text with the summary ";
    $text .= !empty($summary) ? $summary . ", " : "";
    $text .= !empty($audience) ? "the text should be focused on the audience " . $audience . ", " : "";
    $text .= !empty($keywords) ? "using the keywords " . $keywords . ", " : "";
    $text .= !empty($minParagraphs) ? "with a minimum of " . $minParagraphs . " paragraphs " : "";
    $text .= !empty($maxParagraphs) ? "and a maximum of " . $maxParagraphs . " paragraphs, " : "";
    $text .= !empty($textTone) ? "the text tone should be " . $textTone . ", " : "";
    $text .= !empty($writingStyle) ? "the writing style should be " . $writingStyle . ", " : "";
    $text .= !empty($language) ? "and in the language " . $language . "." : "";

    $getPrompt = $prompts->get($id_prompt);
    $messages = array();
    $messages[] = array(
        "role" => "system",
        "content" => $getPrompt->prompt
    );

    $messages[] = array(
        "role" => "user",
        "content" => $text
    );    

    $data = [
        "messages" => $messages,
        "model" => $getPrompt->API_MODEL,
        "temperature" => $getPrompt->temperature,
        "max_tokens" => $config->max_tokens_gpt,
        "frequency_penalty" => $getPrompt->frequency_penalty,
        "presence_penalty" => $getPrompt->presence_penalty,
        "stream" => true
    ];

    $url = "https://api.openai.com/v1/chat/completions";
    $headers = [
        "Authorization: Bearer " . $config->openai_api_key,
        "Content-Type: application/json"
    ];

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_WRITEFUNCTION => function ($curl, $data) use (&$chunk_buffer) {
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($httpCode != 200) {
                // Tente decodificar a resposta
                $response = json_decode($data, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Se a resposta foi decodificada com sucesso, imprima a mensagem de erro
                    echo 'data: ' . json_encode(['error' => ['message' => $response['error']['message']]]) . PHP_EOL;
                } else {
                    // Se a resposta não pôde ser decodificada como JSON, imprima a resposta como está, mas no formato JSON
                    echo 'data: ' . json_encode(['error' => ['message' => $data]]) . PHP_EOL;
                }
            } else {
                $chunk_buffer .= $data;
                echo 'data: ' . $data . PHP_EOL;
                ob_flush();
                flush();
            }
            return strlen($data);
        },
    ]);

    curl_exec($curl);
