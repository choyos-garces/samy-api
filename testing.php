<?php

use GuzzleHttp\Middleware;

require __DIR__.'/vendor/autoload.php';

$headers = [
  "Content-type" => "application/json"
];

$client = new \GuzzleHttp\Client([
    "base_uri" => "http://localhost/",
    "headers" => $headers
]);

$data = [
    "_username" => "hola",
    "_password" => "tu"
];

$response = $client->request('POST', '/samy/app_dev.php/auth', [
    'form_params' => [
            "_username" => "hola",
            "_password" => "tu"
        ]
]);

echo "Content-type: ".$response->getHeaderLine('content-type')."\n";
echo "Body: ".$response->getBody();
echo "\n\n";die;