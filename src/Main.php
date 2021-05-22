<?php


namespace App;


use GuzzleHttp\Client;

class Main
{
    private Client $client;

    private static string $DATA_DIR = 'data';
    private static string $FILENAME = 'indexes.txt';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function processRequest()
    {
        $response = $this->client->request("GET",$_ENV['ADDRESS'],[
            'query'=>[
                'limit'=>(int)$_ENV['LIMIT']
            ]
        ]);

        $statusCode = $response->getStatusCode();

        if($statusCode == 200){
            $indexesOfUsers = $this->getUsersList($response->getBody());
            $this->saveIndexes($indexesOfUsers);
        }else if ($statusCode>=400 && $statusCode<500 ){
            throw new \Exception("Client Error: ".$statusCode);
        }else if ($statusCode>=500){
            throw new \Exception("Server Error: ".$statusCode);
        }else{
            throw new \Exception("Something went wrong! Status Code: ".$statusCode);
        }

    }

    public function transformDataToTransactionsArray(array $transactions): array
    {
        $transactionsArr = [];
        foreach ($transactions as $transaction){
            array_push($transactionsArr,new Transaction($transaction['value'],$transaction['confirmed']));
        }

        return $transactionsArr;
    }

    public function getTransactionsByDateAndValue(array $transactions):array
    {
        $date = new \DateTime($_ENV['DATETIME']);
        $transactionsArr = [];
        foreach ($transactions as $transaction){
            if($transaction instanceof Transaction && $date<= $transaction->getConfirmed() &&
                $transaction->getValue() >=100000 && $transaction->getValue() < 1000000){
                $transactionsArr[]=$transaction;
            }
        }

        //sort array by the oldest datetime
        return array_reverse($transactionsArr);
    }

    public function getUsersList(string $body): array
    {
        $decodedData = json_decode($body,true);
        $transactions = $this->transformDataToTransactionsArray($decodedData["txrefs"]);
        $transactions = $this->getTransactionsByDateAndValue($transactions);
        var_dump($transactions);

        $indexesOfUsers = Transaction::getTransactionsArray($transactions);
//        var_dump($indexesOfUsers);
        return $indexesOfUsers;
    }

    public function saveIndexes(array $indexes)
    {
        $dir = __DIR__."/../".self::$DATA_DIR;
        if(!is_dir($dir)){
            mkdir($dir);
        }

        //remove duplicated elements and separated indexes by new line sign
        $indexesStr = implode("\r\n",array_unique($indexes));
        file_put_contents($dir."/".self::$FILENAME,$indexesStr);
    }
}

