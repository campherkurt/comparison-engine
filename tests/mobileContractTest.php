<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \ComparisonEngine\Api;

class MobileContractTests extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $api = new Api('test_id', 'test_secret');
        $this->model = $api->getModel('mobile_contract');
    }

    public function testGetAll()
    {

        for($i=0; $i<=9; $i++){
          $params = $this->getPostData(['network'=>'cellcTest' . $i]);
          $resp = $this->model->createItem($params);
        }

        $resp = $this->model->getAllItems();
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals(false, empty($resp['data']));
        $this->assertGreaterThanOrEqual(9, $resp['data']);
        $this->assertEquals(false, empty($resp['meta']));
        $this->assertEquals('mobile_package item(s) successfully retrieved.', $resp['message']);
    }

    public function testCreateItem(){
        $params = $this->getPostData(['network'=>'cellctest']);
        $resp = $this->model->createItem($params);
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals(false, empty($resp['data']));
        $this->assertEquals(true, empty($resp['meta']));
        $this->assertEquals('mobile_package item successfully created.', $resp['message']);
        $this->assertEquals('cellctest', $resp['data']['network']['value']);
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
        $params = $this->getPostData(['network'=>'cellcTesA']);
        $resp   = $this->model->createItem($params);
        $id     = $resp['data']['_id'];
        $resp = $this->model->getItemById($id);
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals('cellcTesA', $resp['data']['network']['value']);
        $this->assertEquals(false, empty($resp['meta']));
        $this->assertEquals('mobile_package item(s) successfully retrieved.', $resp['message']);
    }

    /**
     * Check for one Mobile item. Fail.
     * @group integrateMobile
     * @return void
     */
   public function testGetSingleItemByIdFail()
    {
        $resp = $this->model->getItemById('999999999999');
        $this->assertEquals(false, $resp['success']);
        $this->assertEquals(true, empty($resp['data']));
        $this->assertEquals(true, empty($resp['meta']));
        $this->assertEquals('mobile_package item(s) could not be retrieved.', $resp['message']);
    }


    public function testSearchQueryPassWithBrand()
    {
      // Create the items
      $resp   = $this->model->createItem($this->getPostData(['network'=>'cellctestBB', 'call_charged_to_own_network_peak'=>0.55]));
      $resp   = $this->model->createItem($this->getPostData(['network'=>'cellctestBB', 'call_charged_to_own_network_peak'=>0.85]));
      $resp   = $this->model->createItem($this->getPostData(['network'=>'cellctestBB', 'call_charged_to_own_network_peak'=>0.95]));
      $resp   = $this->model->createItem($this->getPostData(['network'=>'cellctestBB', 'call_charged_to_own_network_peak'=>2.50]));
      $resp = $this->model->getBySearch([
          'network'=>'cellctestBB',
          'call_charged_to_own_network_peak'=>[0.55,0.95],
          'sort'=>'call_charged_to_own_network_peak',
          'direction'=>'asc'
      ]);
      //var_dump($resp['data']);
      $this->assertEquals(true, $resp['success']);
      $this->assertEquals('cellctestBB', $resp['data'][0]['network']['value']);
      $this->assertEquals(0.55, $resp['data'][0]['call_charged_to_own_network_peak']['value']);
      $this->assertEquals(false, empty($resp['meta']));
      $this->assertEquals('mobile_package item(s) successfully retrieved.', $resp['message']);
    }


//----------------------------- Helpers ----------------------------------//
    private function getPostData($newData){
        $data = [
            'leagcy_contract_id'            => '1',
            'url'                           => 'https://www.cellc.co.za/cell-phone-deals/contract/apple-iphone-6-plus-16gb-on-infinity-select-handset-deal',
            'model'                         => 'iPhone 6 16GB',
            'brand'                         => 'Apple',
            'network'                       => 'CellC',
            'price'                         => 1499,
            'package'                       => 'Infinity Select Handset',
            'extra'                         => '',
            'description'                   => 'Apple iPhone 6',
            'detailed_data'                 => '',
            'detailed_sms'                  => '',
            'device_cost'                   => '',
            'terms'                         => 24,
            'monthly_fee'                   => 1399,
            'total_mins_included'           => 9999,
            'total_sms_included'            => 9999,
            'total_data'                    => 10000,
            'connection_fee'                => 195,
            'call_charged_to_own_network_peak'     => 0.99,
            'call_charged_to_own_network_off_peak' => 0.99,
            'call_charged_to_other_network_peak'   => 0.99,
            'call_charged_to_other_network_off_peak' => 0.99,
            'sms_charge_peak'               => 0.99,
            'sms_charge_off_peak'           => 0.99,
            'data_charge_peak'              => 0.69,
            'data_charge_off_peak'          => 0.99,
            'status'                        => true,
            'apple_deals'                   => 1,
            'mtn_deals'                     => 3,
            'vodacom_deals'                 => 2,
            'samsung_deals'                 => 0,
            'tags'                          => ''
        ];

      return array_merge($data, $newData);
    }
}

?>
