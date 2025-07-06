<?php

if(isset($_POST))
{
    $env = file_get_contents('.env');
    $lines = explode("\n", $env);

    foreach($lines as $line)
    {
        preg_match("/(?<key>[^#]+)\=(?<value>.+)/", $line, $matches);
        if($matches['value'] !== null)
        {
            putenv(trim($line));
        }
    }

    $accessToken = new \League\OAuth2\Client\Token\AccessToken();
    $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);  
    $apiClient->setAccessToken($accessToken)
        ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
        ->onAccessTokenRefresh(
            function (\League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
                saveToken(
                    [
                        'accessToken' => $accessToken->getToken(),
                        'refreshToken' => $accessToken->getRefreshToken(),
                        'expires' => $accessToken->getExpires(),
                        'baseDomain' => $baseDomain,
                    ]
                );
            });
}
?>