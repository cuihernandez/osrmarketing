curl https://api.openai.com/v1/files \
<?php

$OPENAI_API_KEY = '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'; // Replace with your actual OpenAI API key
$FILE_PATH = '../datasets/discovery_meeting.json';  // Replace with the path to your file

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/files');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $OPENAI_API_KEY"
));
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
    'purpose' => 'fine-tune',
    'file' => new CURLFile($FILE_PATH) // If you know the MIME type, you can add it as a second argument
));

// Enable verbose mode
curl_setopt($ch, CURLOPT_VERBOSE, true);

// Execute cURL session and fetch the response
$output = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Print the output
    echo $output;
}

// Close cURL session
curl_close($ch);

?>
