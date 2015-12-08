<?php
namespace ComparisonEngine;

class Mobile {
    private $http;

    function __construct(\ComparisonEngine\Http $httpHandler){
        $this->http = $httpHandler;
        $this->http->setResource('mobile');
        $this->http->setApiVersion('1');
        $this->http->setDomain('comparison-engine.monsterlab.co.za');
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
