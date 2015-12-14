<?php
namespace ComparisonEngine;

class Api {
    // Set the credentials
    private $clientName;
    private $clientSecret;

    public function __construct($clientName, $clientSecret) {
       $this->clientName   = $clientName;
       $this->clientSecret = $clientSecret;
       return $this;
    }

    public function getModel($model) {
        $http = new \ComparisonEngine\Http($this->clientName, $this->clientSecret);
        switch ($model) {
            case 'mobile':
                return new \ComparisonEngine\Resources\Mobile($http);
                break;
            case 'mobile_contract':
                return new \ComparisonEngine\Resources\MobileContract($http);
                break;
            case 'broadband':
                return new \ComparisonEngine\Resources\Broadband($http);
                break;
        }
    }

    // return requested model class


}

 ?>
