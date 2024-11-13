<?php

namespace App\Services;

use League\OAuth2\Client\Provider\GenericProvider;

class OAuthService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new GenericProvider([
            'clientId'                => env('OAUTH_CLIENT_ID'),
            'clientSecret'            => env('OAUTH_CLIENT_SECRET'),
            'urlAccessToken'          => env('OAUTH_URL_ACCESS_TOKEN'),
            'urlAuthorize' => env('OAUTH_URL_AUTHORIZE'),
            'urlResourceOwnerDetails' => env('OAUTH_URL_RESOURCE_OWNER_DETAILS'),
            #'scopes'                  => env('OAUTH_SCOPES')
        ]);
    }

    public function getAccessToken()
    {
        return $this->provider->getAccessToken('client_credentials');
    }
}
