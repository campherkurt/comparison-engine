<?php
namespace ComparisonEngine;

class Api {
    // Set the credentials
    private $clientName;
    private $clientSecret;

    public function __construct($clientName, $clientSecret, $serverDomain) {
       $this->clientName   = $clientName;
       $this->clientSecret = $clientSecret;
       $this->serverDomain = $serverDomain;
       return $this;
    }

    public function getModel($model) {
        $http = new \ComparisonEngine\Http($this->clientName, $this->clientSecret, $this->serverDomain);
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
            case 'bank_savings':
                return new \ComparisonEngine\Resources\BankSavings($http);
                break;
            case 'bank_cheques':
                return new \ComparisonEngine\Resources\BankCheque($http);
                break;
            case 'bank_credit_cards':
                return new \ComparisonEngine\Resources\BankCredit($http);
                break;
            case 'dating_site':
                return new \ComparisonEngine\Resources\DatingSite($http);
                break;
        }
    }

    // return requested model class


}

 ?>
