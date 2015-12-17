<?php
namespace ComparisonEngine\Resources;

use \ComparisonEngine\Resources\BaseModel;
class BankSavings extends BaseModel{
    private $http;

    function __construct(\ComparisonEngine\Http $http){
        parent::__construct($http, 'bank-savings');
    }
}

 ?>
