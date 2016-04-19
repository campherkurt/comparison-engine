<?php
namespace ComparisonEngine\Resources;

use \ComparisonEngine\Resources\BaseModel;

class Casino extends BaseModel
{
    private $http;

    public function __construct(\ComparisonEngine\Http $http)
    {
        parent::__construct($http, 'casino');
    }
}
