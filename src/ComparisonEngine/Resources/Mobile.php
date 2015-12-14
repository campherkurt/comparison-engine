<?php
namespace ComparisonEngine\Resources;

use \ComparisonEngine\Resources\BaseModel;
class Mobile extends BaseModel{
    private $http;

    function __construct(\ComparisonEngine\Http $http){
        parent::__construct($http, 'mobile');
    }
}

 ?>
