<?php
namespace ComparisonEngine;

use \ComparisonEngine\AuthToken;
use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\ClientException;
use \League\OAuth2\Client\Provider\GenericProvider;

class Http {
    private $resource;
    private $apiVersion;
    private $domain;
    private $fullResourceUrl;
    private $httpProtocol = 'http';
    private $accessToken;

    public function __construct($clientName, $clientSecret, $domain){
      $this->domain = $domain;
      $this->httpClient = new Client();
      $this->authToken  = new AuthToken($clientName, $clientSecret, $this->domain); //These need to be environemnt variables
    }

    private function getApiUrl() {
        if (!$this->fullResourceUrl){
            $this->fullResourceUrl = "{$this->httpProtocol}://{$this->domain}/api/v{$this->apiVersion}/{$this->resource}";
        }
        return $this->fullResourceUrl;
    }

    public function setResource($resource) {
        $this->resource = $resource;
    }

    public function setApiVersion($apiVersion) {
        $this->apiVersion = $apiVersion;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function getDomain() {
        return $this->domain;
    }

    public function setHttpProtocol($httpProtocol){
        $this->httpProtocol = $httpProtocol;
    }

    private function getAccessToken() {
        return $this->authToken->getAuthToken();
    }

    private function handleResponse($response) { // Psr\Http\Message\ResponseInterface
        $headers = $response->getHeaders();
        $body    = $response->getBody();
        if (
              $headers['Content-Type'][0] == 'application/json'
           )
        {
            return json_decode($body->getContents(), true);
            /* Add logging here.
             * Log every response for auditing when things go wrong.
             * Info to Log:
             *   - Http code
             *   - url
             *   - Error Message if it exists
             *   - Body Contents
             */
        }
        throw new \Exception('Api response is not json:' . $body->getContents());
    }

    public function makeGetRequest($apiPath, array $params = []) {
        $url = $this->getApiUrl() . $apiPath;
        $params['access_token'] = $this->getAccessToken();
        try {
            $response = $this->httpClient->get($url, ['query'=>$params]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }
        return $this->handleResponse($response);
    }

    public function makePostRequest($apiPath, array $params = []) {
        $url = $this->getApiUrl() . $apiPath;
        $params['access_token'] = $this->getAccessToken();
        //var_dump($params);
        try {
            $response = $this->httpClient->request(
               'POST',
               $url,
               ['form_params'=>$params]
             );
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }
        return $this->handleResponse($response);
    }


}

 ?>
