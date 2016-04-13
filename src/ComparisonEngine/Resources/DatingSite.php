<?php
namespace ComparisonEngine\Resources;

use \ComparisonEngine\Resources\BaseModel;

class DatingSite extends BaseModel
{
    private $http;

    public function __construct(\ComparisonEngine\Http $http)
    {
        parent::__construct($http, 'dating-sites');
    }
}
