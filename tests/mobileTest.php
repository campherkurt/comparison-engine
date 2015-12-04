<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \ComparisonEngine\Mobile;
use \ComparisonEngine\Http;

class mobileTests extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $httpHandler  = new Http;
        $this->mobile = new Mobile($httpHandler);
    }

    public function testGetAll()
    {

        for($i=0; $i<=9; $i++){
          $params = $this->getPostData(['brand'=>'samsung_and_' . $i]);
          $resp = $this->mobile->createItem($params);
        }

        $resp = $this->mobile->getAllItems();
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals(false, empty($resp['data']));
        $this->assertGreaterThanOrEqual(9, $resp['data']);
        $this->assertEquals(false, empty($resp['meta']));
        $this->assertEquals('mobile item(s) successfully retrieved.', $resp['message']);
    }

    public function testCreateItem(){
        $params = $this->getPostData(['brand'=>'samsung', 'min_contract_price'=>10]);
        $resp = $this->mobile->createItem($params);
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals(false, empty($resp['data']));
        $this->assertEquals(true, empty($resp['meta']));
        $this->assertEquals('mobile item successfully created.', $resp['message']);
    }
/*
    public function testCreateItemFail(){
        $params = $this->getPostData(['brand'=>'', 'min_contract_priceError'=>10]);
        var_dump($params);
        $resp = $this->mobile->createItem($params);
        //var_dump($resp);
        $this->assertEquals(false, $resp['success']);
        $this->assertEquals(true, empty($resp['data']));
        $this->assertEquals(false, empty($resp['meta']));
        $this->assertEquals('mobile item successfully created.', $resp['message']);
    }
*/
    /**
     * Check for one Mobile item.
     * @group integrateMobile
     * @return void
     */
    public function testGetSingleItemById()
    {
        $params = $this->getPostData(['brand'=>'samsungTest', 'min_contract_price'=>10]);
        $resp   = $this->mobile->createItem($params);
        $id     = $resp['data']['_id'];
        $resp = $this->mobile->getItemById($id);
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals('samsungTest', $resp['data']['brand']['value']);
        $this->assertEquals(false, empty($resp['meta']));
        $this->assertEquals('mobile item(s) successfully retrieved.', $resp['message']);
    }

    /**
     * Check for one Mobile item. Fail.
     * @group integrateMobile
     * @return void
     */
    public function testGetSingleItemByIdFail()
    {
        $resp = $this->mobile->getItemById('999999999999');
        $this->assertEquals(false, $resp['success']);
        $this->assertEquals(true, empty($resp['data']));
        $this->assertEquals(true, empty($resp['meta']));
        $this->assertEquals('mobile item(s) could not be retrieved.', $resp['message']);
    }


    public function testSearchQueryPassWithBrand()
    {
      // Create the item
      $params = $this->getPostData(['brand'=>'samsungTestSearch', 'min_contract_price'=>22]);
      $resp   = $this->mobile->createItem($params);
      $resp = $this->mobile->getBySearch([
          'brand'=>'samsungTestSearch'
      ]);
      //var_dump($resp['data']);
      $this->assertEquals(true, $resp['success']);
      $this->assertEquals('samsungTestSearch', $resp['data'][0]['brand']['value']);
      $this->assertEquals(false, empty($resp['meta']));
      $this->assertEquals('mobile item(s) successfully retrieved.', $resp['message']);
    }

    public function testSearchQueryPassWithBrandAndPrice()
    {
      // Create the item
      $params = $this->getPostData(['brand'=>'samsungTestSearchTwo', 'min_contract_price'=>33]);
      $resp   = $this->mobile->createItem($params);
      $resp = $this->mobile->getBySearch([
          'brand'=>'samsungTestSearchTwo',
          'min_contract_price'=>[32,34]
      ]);
      var_dump($resp['data']);
      $this->assertEquals(true, $resp['success']);
      $this->assertEquals('samsungTestSearchTwo', $resp['data'][0]['brand']['value']);
      $this->assertEquals(33, $resp['data'][0]['min_contract_price']['value']);
      $this->assertEquals(false, empty($resp['meta']));
      $this->assertEquals('mobile item(s) successfully retrieved.', $resp['message']);
    }

//----------------------------- Helpers ----------------------------------//
    private function getPostData($newData){
      return array_merge([
        'brand'              =>'apple',
         'model'              =>'iPhone 5s',
         'dimension'          =>'123.8x58.6x7.6',
         'description'        =>"test description",
         'OS'                 =>'ios7',
         'talk_time'          =>10,
         'standby_time'       =>250,
         'camera'             =>8,
         'internal_memory'    =>32,
         'wifi'               =>1,
         'min_contract_price' =>349,
         'tags'               =>'mobile|cheap'
      ], $newData);
    }
}

?>
