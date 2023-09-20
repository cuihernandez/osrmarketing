<?php

$OPENAI_API_KEY = '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';
$file_path = '../datasets/recruiting.jsonl';

$command = "curl https://api.openai.com/v1/files " .
           "-H \"Authorization: Bearer $OPENAI_API_KEY\" " .
           "-F \"purpose=fine-tune\" " .
           "-F \"file=@$file_path\"";

$response = shell_exec($command);
echo $response;

?>

