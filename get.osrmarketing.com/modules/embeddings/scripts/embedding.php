<?php

// 1. Read the file contents
$file_path = "../dataset/core_values.txt";
$text_to_embed = file_get_contents($file_path);

// 2. Use cURL to get the embedding
$ch = curl_init('https://api.openai.com/v1/embeddings');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer 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"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "input" => $text_to_embed,
    "model" => "text-embedding-ada-002"
]));

$response = curl_exec($ch);
if(curl_errno($ch)){
    // Handle curl error
    echo 'Curl error: ' . curl_error($ch);
} else {
    // 3. Save the response to the desired location
    $output_path = "../embedded/filename.embedding";
    file_put_contents($output_path, $response);
    echo "Embedding saved to $output_path";
}

curl_close($ch);
?>
