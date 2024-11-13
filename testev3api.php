<?php

function getAccessToken($clientId, $clientSecret, $tokenUrl) {
    $ch = curl_init();

    $data = [
        'grant_type' => 'client_credentials',
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
    ];

    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response . PHP_EOL;
    if (isset($responseData['access_token'])) {
        return $responseData['access_token'];
    } else {
        throw new Exception('Falha ao obter token de acesso');
    }
}

$clientId = 'b5005428f694184f30cfc62494c9caa928403446';
$clientSecret = '64a94c3d0ed049d28af694a2efd7f8fcf5dad0f8b9823e09da4683e3c9d3';
$tokenUrl = 'https://bling.com.br/Api/v3/oauth/token';

try {
    $accessToken = getAccessToken($clientId, $clientSecret, $tokenUrl);
    echo 'Access Token: ' . $accessToken;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
