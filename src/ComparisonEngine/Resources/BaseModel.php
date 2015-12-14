<?php
namespace ComparisonEngine\Resources;

abstract class BaseModel {
    private $http;

    function __construct(\ComparisonEngine\Http $httpHandler, $model, $version = 1, $domain = 'comparison-engine.monsterlab.co.za'){
        $this->http = $httpHandler;
        $this->http->setResource($model);
        $this->http->setApiVersion($version);
        $this->http->setDomain($domain);
    }

    public function getAllItems() {
        return $this->http->makeGetRequest('/');
    }

    public function getBySearch(array $searchQuery) {
        return $this->http->makeGetRequest('/search/', $searchQuery);
    }

    public function createItem(array $params){
        return $this->http->makePostRequest('', $params);
    }

    public function getItemById($id){
        return $this->http->makeGetRequest('/' . $id);
    }
}

 ?>
