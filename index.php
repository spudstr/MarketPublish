<?php

require_once __DIR__ . "/vendor/autoload.php";
header('Content-Type: application/json');
//$manager = new MongoDB\Driver\Manager("mongodb://172.16.0.85:27017/");


$pair = strtolower($_GET["pair"]);
$exchange = strtolower($_GET["exchange"]);
$periods = $_GET["periods"];


if($exchange == "coinbase") { $exchange = "gdax"; }
/*
$query = "[
            'exchange' => '.$exchange.',
            'pair' => '.$pair.'
        ][
            'sort' >= ['CloseTime' => -1]
        ]
}).sort({
    "CloseTime": -1
}).limit('.$periods.');';
*/

//$filter = [['excange' => 'bitfinex', 'pair' => 'ltcusd'], ['CloseTime' => 1, 'ClosePrice' => 1]];

$filter =array(
    "exchange" => $exchange,
     "pair" => $pair
);


$options = array(
    "projection" => array(
                       "CloseTime" => 1,
                       "ClosePrice" => 1,
                       "_id" => -1
                       ),
    "sort" => array(
        "CloseTime" => -1,
    ),
    "limit" => (int)$periods
  );

  try {
  $manager     =   new MongoDB\Driver\Manager("mongodb://localhost:27017");
  $query = new MongoDB\Driver\Query($filter,$options);

  $cursor = $manager->executeQuery('MarketCollector.market_data', $query);
  $dataArray = array();
  $dataArray[] = array();
  foreach ($cursor as $data)
  {
         $dataArray["result"][$periods][] = array($data->CloseTime,$data->ClosePrice);
  }
  } catch (MongoDB\Driver\Exception\Exception $e) {
      $filename = basename(__FILE__);

      echo "The $filename script has experienced an error.\n";
      echo "It failed with the following exception:\n";

      echo "Exception:", $e->getMessage(), "\n";
      echo "In file:", $e->getFile(), "\n";
      echo "On line:", $e->getLine(), "\n";
   }



  $reverseArray = array_reverse($dataArray,true);

  $json_array = json_encode($reverseArray, JSON_PRETTY_PRINT);
  print_r($json_array);





?>
