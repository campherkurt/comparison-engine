<?php
namespace ComparisonEngine\Resources;

use \ComparisonEngine\Resources\BaseModel;
class MobileContract extends BaseModel{
    private $http;

    function __construct(\ComparisonEngine\Http $http){
        parent::__construct($http, 'mobile-package');
    }
}

 ?>
