<?php
namespace ComparisonEngine\Resources;

use BaseModel;

class DatingSite extends BaseModel
{
    private $http;

    public function __construct(\ComparisonEngine\Http $http)
    {
        parent::__construct($http, 'dating-sites');
    }
}
