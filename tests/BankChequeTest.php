<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \ComparisonEngine\Api;

class BankChequeTests extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $api = new Api('test_id', 'test_secret');
        $this->model = $api->getModel('bank_cheques');
    }

    public function testGetAll()
    {

        for($i=0; $i<=9; $i++){
          $params = $this->getPostData(['provider'=>'ABSA_' . $i]);
          $resp = $this->model->createItem($params);
        }

        $resp = $this->model->getAllItems();
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals(false, empty($resp['data']));
        $this->assertGreaterThanOrEqual(9, $resp['data']);
        $this->assertEquals(false, empty($resp['meta']));
        $this->assertEquals('bank_cheques item(s) successfully retrieved.', $resp['message']);
    }

    public function testCreateItem(){
        $params = $this->getPostData(['provider'=>'ABSA3333']);
        $resp = $this->model->createItem($params);
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals(false, empty($resp['data']));
        $this->assertEquals(true, empty($resp['meta']));
        $this->assertEquals('bank_cheques item successfully created.', $resp['message']);
        $this->assertEquals('ABSA3333', $resp['data']['provider']['value']);
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
        $params = $this->getPostData(['provider'=>'ABSA500', 'min_salary'=>3000]);
        $resp   = $this->model->createItem($params);
        $id     = $resp['data']['_id'];
        $resp = $this->model->getItemById($id);
        $this->assertEquals(true, $resp['success']);
        $this->assertEquals('ABSA500', $resp['data']['provider']['value']);
        $this->assertEquals('R3000', $resp['data']['min_salary']['pretty']);
        $this->assertEquals(false, empty($resp['meta']));
        $this->assertEquals('Bank', $resp['meta']['provider']['pretty']);
        $this->assertEquals('bank_cheques item(s) successfully retrieved.', $resp['message']);
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
        $this->assertEquals('bank_cheques item(s) could not be retrieved.', $resp['message']);
    }


    public function testSearchQueryPassWithBrand()
    {
      // Create the items
      $resp   = $this->model->createItem($this->getPostData(['provider'=>'ABSATest000', 'min_salary'=>2000]));
      $resp   = $this->model->createItem($this->getPostData(['provider'=>'ABSATest000', 'min_salary'=>3000]));
      $resp   = $this->model->createItem($this->getPostData(['provider'=>'ABSATest000', 'min_salary'=>0]));
      $resp   = $this->model->createItem($this->getPostData(['provider'=>'ABSATest000', 'min_salary'=>5500]));
      $resp   = $this->model->createItem($this->getPostData(['provider'=>'ABSATest000', 'min_salary'=>7000]));
      $resp = $this->model->getBySearch([
          'provider'=>'ABSATest000',
          'min_salary'=>[3000,7000],
          'sort'=>'min_salary',
          'direction'=>'asc'
      ]);
      //var_dump($resp['data']);
      $this->assertEquals(true, $resp['success']);
      $this->assertEquals('ABSATest000', $resp['data'][0]['provider']['value']);
      $this->assertEquals(3000, $resp['data'][0]['min_salary']['value']);
      $this->assertEquals(false, empty($resp['meta']));
      $this->assertEquals('bank_cheques item(s) successfully retrieved.', $resp['message']);
      $this->assertEquals(true, is_array($resp['data'][0]['benefits']['value']));
    }


//----------------------------- Helpers ----------------------------------//
    private function getPostData($newData){
        $data = [
            'legacy_cheque_id'            => '4',
            'card_type'                    => 'Instant Access',
            'provider'                     => 'ABSA',
            'card_name'                    => 'sum(1)',
            'min_salary'                   => '0',
            'min_balance'                  => '1000',
            'monthly_fee'                  => '0',
            'opening_deposit'              => '15000',
            'cw_own_bank_atm'              => 'R3,50 plus R1,15/R100,00',
            'cw_other_bank_atm'            => 'R3,50 plus R1,15/R100,00',
            'cw_over_the_counter'          => 'R9,50 + 1,15%',
            'cw_pos'                   => 'R5,00',
            'cd_atm'                   => 'R1,15/ R100,00',
            'cd_branch'                => 'R5,95 + 1,25%',
            'ap_eft'                   => 'No Charge',
            'ap_branch'                => 'N/A',
            'ap_mobile'                => 'No Charge',
            'ap_pos'                   => 'N/A',
            'ap_atm'                   => 'R1,00',
            'ap_debit_order'           => 'R3,50',
            'be_branch_counter'        => 'R6,00',
            'be_atm'                   => 'R1,10',
            'be_internet_statement'    => 'No Charge',
            'rebate_1'                   => 'R280,00',
            'rebate_2'                   => 'R280,00',
            'rebate_3'                   => 'R280,00',
            'rebate_4'                   => 'R280,00',
            'rebate_5'                   => 'R280,00',
            'features'                 =>'Unlimited SMS notifications with MyUpdates',
            'valid_id'                 => 'Yes',
            'age_required'             => 'under 16',
            'proof_of_residence'       => 'Yes',
            'url'                      => 'http://www.standardbank.co.za/portal/site/standardbank/menuitem.de435aa54d374eb6fcb695665c9006a0/?vgnextoid=c31ed3be7f96e310VgnVCM10000050ddb60aRCRD',
            'ticw_atm'                 => 'N/A',
            'ticw_branch'              => 'N/A',
            'ticw_pos'                 => 'N/A',
            'ticd_atm'                 => 'N/A',
            'ticd_branch'              => 'N/A',
            'tiap_branch'              => 'N/A',
            'tiap_mobile'              => 'N/A',
            'tiap_pos'                 => 'N/A',
            'tiap_atm'                 => 'N/A',
            'tiap_debit'               => 'N/A',
            'benefits'                 => [
                'value'=>[
                    'The higher the balance, the higher your interest rate will be',
                    'You can make additional deposits at any time',
                    'Your Depositor Plus account can be linked to all your Absa accounts',
                    'You can do inter-account transfers through an ATM, Internet Banking, Cellphone Banking and Telephone Banking (individuals only)',
                    'You have immediate access to your investment'
                ]
            ], // array
            'tags'                     => ''
        ];

      return array_merge($data, $newData);
    }
}

?>
