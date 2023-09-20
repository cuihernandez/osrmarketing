<?php
function listFineTuningJobs($limit = 10) {
    $API_KEY = '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';
    $url = 'https://api.openai.com/v1/fine_tuning/jobs'; // Let's start by listing engines

    $headers = [
        'Authorization: Bearer ' . $API_KEY,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
    $response = curl_exec($ch);

    if(curl_errno($ch)){
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    return json_decode($response, true);
}

// Usage:
$jobs = listFineTuningJobs();
print_r($jobs);
?>
