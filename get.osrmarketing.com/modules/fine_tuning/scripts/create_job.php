<?php

// Set your API key
$OPENAI_API_KEY = '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';

// Initialize cURL session
$ch = curl_init();

// The data you want to send
$data = [
    "training_file" => "file-37AhxrUamAEWboVdKUuTCbc4",  // <-- replace with your file ID
    "model" => "gpt-3.5-turbo-0613"
];

// Set cURL options
curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/fine_tuning/jobs");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $OPENAI_API_KEY"
]);

// For verbose output
curl_setopt($ch, CURLOPT_VERBOSE, true);
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

// Execute cURL session and fetch the response
$response = curl_exec($ch);

// If there was an error in cURL, display it
if (curl_errno($ch)) {
    rewind($verbose);
    $verboseLog = stream_get_contents($verbose);
    echo "cURL Error: " . curl_error($ch);
    echo "\nVerbose information:\n", htmlspecialchars($verboseLog), "\n";
} else {
    // Decoding the JSON response to an associative array
    $decodedResponse = json_decode($response, true);

    // Check and print the response (based on your requirements)
    echo "Response:\n";
    print_r($decodedResponse);
}

// Close the cURL session
curl_close($ch);
?>


