<?php declare(strict_types=1);

namespace FGhazaleh\Storage\GoogleSheets;

use FGhazaleh\Exceptions\InvalidArgsException;
use Google_Client;

final class GoogleClientFactory
{
    /**
     * @param array $config
     * @return Google_Client
     */
    public static function make(array $config): Google_Client
    {
        $client = new Google_Client();

        if (!isset($config['scopes'])) {
            throw new InvalidArgsException('"scopes" config is missing.');
        }

        if (!isset($config['client_id'])) {
            throw new InvalidArgsException('"client_id" config is missing.');
        }

        if (!isset($config['client_secret'])) {
            throw new InvalidArgsException('"client_secret" config is missing.');
        }

        $client->addScope($config['scopes']);
        $client->setClientId($config['client_id']);
        $client->setClientSecret($config['client_secret']);
        $client->setRedirectUri(
            isset($config['redirect_url']) ?
                $config['redirect_url'] :
                'urn:ietf:wg:oauth:2.0:oob'
        );
        $client->setAccessType('offline');

        $credentialsPath = isset($config['token_file']) ? $config['token_file'] : 'token.json';

        $accessToken = null;
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            $accessToken = static::requestAuthorizationFromUser($client, $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        static::refreshTokenIfTokenExpired($client, $credentialsPath);

        return $client;
    }

    /**
     * Use to request an authorization from the user.
     *
     * @param Google_Client $client
     * @param string $credentialsPath
     * @return array
     */
    private static function requestAuthorizationFromUser(Google_Client $client, string $credentialsPath): array
    {
        // Request authorization from the user
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        static::storeAccessToken($accessToken, $credentialsPath);
        printf("Credentials saved to %s\n", $credentialsPath);

        return $accessToken;
    }

    /**
     * Use to refresh the access token if it's expired.
     *
     * @param Google_Client $client
     * @param string $credentialsPath
     */
    private static function refreshTokenIfTokenExpired(Google_Client $client, string $credentialsPath): void
    {
        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            // Store the credentials to disk.
            static::storeAccessToken($client->getAccessToken(), $credentialsPath);
        }
    }

    /**
     * Use to store the access token json to disk.
     *
     * @param array $accessToken
     * @param string $credentialsPath
     */
    private static function storeAccessToken(array $accessToken, string $credentialsPath): void
    {
        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
    }
}
