<?php


namespace Tests;


use App\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testGetTransactionsArray():void
    {
        $amount_of_transactions = 5;
        $testArr = FakeData::createFakeTransactions($amount_of_transactions);


        $resultArr = Transaction::getTransactionsArray($testArr);

        $this->assertIsArray($resultArr);
        $this->assertCount($amount_of_transactions,$resultArr);
        for($i=0;$i<$amount_of_transactions;$i++){
            $this->assertEquals($testArr[$i]->getValue(),$resultArr[$i]);
        }

    }
}