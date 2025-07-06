<?php


if (isset($_POST))
{
    $env = file_get_contents('.env');
    $lines = explode("\n", $env);

    foreach ($lines as $line)
    {
        preg_match("/(?<key>[^#]+)\=(?<value>.+)/", $line, $matches);
        if ($matches['value'] !== null)
        {
            putenv(trim($line));
        }
    }

    $state = bin2hex(random_bytes(16 / 2));
    $_SESSION['state'] = $state;
    $clientId = $_ENV['CLIENT_ID'];
    $clientSecret = $_ENV['CLIENT_SECRET'];
    $redirectUri = $_ENV['REDIRECT_URI'];
    $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
        
    $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
        'state' => $state,
        'mode' => 'post_message', //post_message - редирект произойдет в открытом окне, popup - редирект произойдет в окне родителе
    ]);

    header('Location: ' . $authorizationUrl);
}
