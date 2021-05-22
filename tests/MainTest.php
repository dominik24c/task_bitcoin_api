<?php

namespace Tests;

use App\Main;
use App\Transaction;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{
    private Main $main;
    private array $fakeData;

    protected function setUp():void
    {
        parent::setUp();
        $_ENV["DATETIME"] = "2015-04-04 00:00:00";

        $mock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->main = new Main($mock);

        $this->fakeData = [
            [
                'value'=>434123,
                'confirmed'=>'2015-04-05 00:00:00'
            ],
            [
                'value'=>634129,
                'confirmed'=>'2015-04-06 00:00:00'
            ],
            [
                'value'=>534123,
                'confirmed'=>'2015-04-07 00:00:00'
            ],
        ];
    }

    public function testGetTransactionsByDateAndValue():void
    {
        $confirmedDate = "2018-04-04 00:00:00";
        $amount_of_transactions = 7;
        $fakeTransactions = FakeData::createFakeTransactions($amount_of_transactions);
        $fakeTransactions[0]->setValue(2123);
        $fakeTransactions[1]->setConfirmed(new \DateTime("2011-04-04 00:00:00"));
        $fakeTransactions[2]->setConfirmed(new \DateTime($confirmedDate));
        $fakeTransactions[]="hello there!";

        $resultArr = $this->main->getTransactionsByDateAndValue($fakeTransactions);
        $this->assertIsArray($resultArr);
        $this->assertCount($amount_of_transactions-2,$resultArr);
        foreach ($resultArr as $transaction){
            $this->assertInstanceOf(Transaction::class, $transaction);
        }
        $this->assertTrue(new \DateTime($confirmedDate) == end($resultArr)->getConfirmed());
    }

    public function testTransformDataToTransactionsArray()
    {
        $resultArr = $this->main->transformDataToTransactionsArray($this->fakeData);

        $this->assertIsArray($resultArr);
        $this->assertCount(count($this->fakeData),$resultArr);
        foreach ($resultArr as $transaction){
            $this->assertInstanceOf(Transaction::class, $transaction);
        }
    }

    public function testGetUsersList()
    {
        $fakeArr = [
            "txrefs"=>$this->fakeData
        ];
        $fakeJson =json_encode($fakeArr);

        $indexes = $this->main->getUsersList($fakeJson);

        $amount_of_transactions =count($this->fakeData);
        $this->assertIsArray($indexes);
        $this->assertCount($amount_of_transactions,$indexes);

        for($i=0;$i<$amount_of_transactions;$i++){
            $this->assertEquals($this->fakeData[$amount_of_transactions-$i-1]['value'], $indexes[$i]);
        }
    }
}