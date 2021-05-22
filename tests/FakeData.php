<?php


namespace Tests;


use App\Transaction;

class FakeData
{
    public static function createFakeTransactions(int $amount_of_transactions):array
    {
        $testArr = [];
        for($i=0;$i<$amount_of_transactions;$i++){
            $testArr[]=new Transaction(rand(100000,800000),"2017-04-14 00:00:00");
        }

        return $testArr;
    }
}