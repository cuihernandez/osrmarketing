<?php

$openaiApiKey = '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';
$jobId = 'ftjob-Vt9HWBa8puNoIwnBqalIAKtz';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/fine_tuning/jobs/$jobId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $openaiApiKey"));

$response = curl_exec($ch);
if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    echo $response;
}

curl_close($ch);
?>