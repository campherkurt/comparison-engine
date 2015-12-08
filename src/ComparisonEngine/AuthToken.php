<?php
namespace ComparisonEngine;

use \League\OAuth2\Client\Provider\GenericProvider;

class AuthToken {
    private $accessToken;

    public function __construct($clientId, $clientSecret) {
        $this->getStoredToken();
        $this->provider = new GenericProvider([
          'clientId'                => $clientId,    // The client ID assigned to you by the provider
          'clientSecret'            => $clientSecret,    // The client password assigned to you by the provider
          // The info is not needed but expected.
          'redirectUri'             => 'http://tests.dev/outh2_client/redirect.php/',
          'urlAuthorize'            => 'http://comparison-engine.monsterlab.co.za/access_token',
          'urlAccessToken'          => 'http://comparison-engine.monsterlab.co.za/token',
          'urlResourceOwnerDetails' => 'http://comparison-engine.monsterlab.co.za/resource'
        ]);
    }
    public function getAuthToken() {
        if (!$this->accessToken || $this->accessToken->hasExpired()) {
            try {
                $this->accessToken = $this->provider->getAccessToken('client_credentials');
            }
            catch (Exception $e) {
                echo "###### Error occured while getting auth token";
                // Add LOg here
            }
        }
        return $this->accessToken->getToken();

    }

    private function getStoredToken() {
        //

    }

}


 ?>
