<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \ComparisonEngine\Api;

class BroadbandTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $api = new Api('test_id', 'test_secret', 'comparison-engine.monsterlab.co.za');
        $this->model = $api->getModel('broadband');
    }

    public function testGetAll()
    {

        for($i=0; $i<=9; $i++){
          $params = $this->getPostData(['provider'=>'afroheist' . $i]);
          $resp = $this->model->createItem($params);
        }

        $resp = $this->model->getAllItems();
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals(false, empty($resp['data']));
        $this->assertGreaterThanOrEqual(9, $resp['data']);
        $this->assertEquals(false, empty($resp['meta']));
        $this->assertEquals('broadband item(s) successfully retrieved.', $resp['message']);
    }

    public function testCreateItem(){
        $params = $this->getPostData(['provider'=>'afroheist']);
        $resp = $this->model->createItem($params);
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals(false, empty($resp['data']));
        $this->assertEquals(true, empty($resp['meta']));
        $this->assertEquals('broadband item successfully created.', $resp['message']);
        $this->assertEquals('afroheist', $resp['data']['provider']['value']);
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
        $params = $this->getPostData(['provider'=>'afroheist']);
        $resp   = $this->model->createItem($params);
        $id     = $resp['data']['_id'];
        $resp = $this->model->getItemById($id);
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals('afroheist', $resp['data']['provider']['value']);
        $this->assertEquals('Provider', $resp['meta']['provider']['pretty']);
        $this->assertEquals(false, empty($resp['meta']));
        $this->assertEquals('broadband item(s) successfully retrieved.', $resp['message']);
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
        $this->assertEquals('broadband item(s) could not be retrieved.', $resp['message']);
    }


    public function testSearchQueryPassWithBrand()
    {
      // Create the items
      $resp   = $this->model->createItem($this->getPostData(['provider'=>'afroheistDDD', 'cost_per_gig'=>71]));
      $resp   = $this->model->createItem($this->getPostData(['provider'=>'afroheistDDD', 'cost_per_gig'=>73]));
      $resp   = $this->model->createItem($this->getPostData(['provider'=>'afroheistDDD', 'cost_per_gig'=>75]));
      $resp   = $this->model->createItem($this->getPostData(['provider'=>'afroheistDDD', 'cost_per_gig'=>77]));
      $resp = $this->model->getBySearch([
          'provider'=>'afroheistDDD',
          'cost_per_gig'=>[73, 77],
          'sort'=>'cost_per_gig',
          'direction'=>'desc'

      ]);
      //var_dump($resp['data']);
      $this->assertEquals(true, $resp['success']);
      $this->assertEquals('afroheistDDD', $resp['data'][0]['provider']['value']);
      $this->assertEquals(77, $resp['data'][0]['cost_per_gig']['value']);
      $this->assertEquals(false, empty($resp['meta']));
      $this->assertEquals('Cost Per Gig', $resp['meta']['cost_per_gig']['pretty']);
      $this->assertEquals('broadband item(s) successfully retrieved.', $resp['message']);
    }


//----------------------------- Helpers ----------------------------------//
    private function getPostData($newData){
        $data = [
          'url'                =>'http://thingthing.thing',
          'provider'           =>'Afrihost',
          'usage'              =>'75',
          'usagetype'          =>'capped',
          'anytime'            =>'75',
          'nightime'           =>'0',
          'extra_data'         =>'75 Gb Free per Month until December 2015',
          'cost_per_gig'       =>'8.54',
          'once_off_cost'      =>'0',
          'price'              =>'764',
          'speed'              =>'40 mbps',
          'unshaped'           =>true,
          'terms'              =>'Month to Month',
          'line'               =>false,
          'router'             =>false,
          'connection_fee'     =>'0',
          'immediate_activation' =>true,
          'support'              =>'24-Jul',
          'recommended_use'      =>'Home, Office, Business',
          'concurrent_connections' =>'5',
          'ip_adress'              =>'dynamic',
          'web_mail'               =>'5 mailboxes',
          'top_up_cost_per_gig'    =>'7',
          'line_rental'            =>'',
          'router_details'         =>'',
          'broadband_id'           =>'258',
          'description'            =>'Choose the latest Afrihost broadband deal for just R764.00. Use the 75GB of data running on a very fast 40MBPS speed to surf more for longer. Line will also receive 24/7 support. Make the most of the bonus of 5 mailboxes that come with this ADSL deal.',
          'total_data'             =>'150',
          'disclaimer'             =>true,
          'line_speed_search'      =>'40',
          'total_min_included'     =>'',
          'status'                 =>true,
          'provider_details'       =>'FREE Double Anytime Data on top of the normal monthly data allocation, until 31 December 2015',
          'dealscapped'            =>1,
          'dealsuncapped'          =>'',
          'tags'                   =>''
        ];

      return array_merge($data, $newData);
    }
}

?>
