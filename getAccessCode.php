<?php

if($_GET['state'] !== $_SESSION['state'])
{
    header('Location: /');
    exit;
}

$apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
$accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);
$_SESSION['accessToken'] = $accessToken;
header('Location: /handleForm');