<?php

require_once __DIR__ . "/vendor/autoload.php";

$manager = new MongoDB\Driver\Manager("mongodb://172.16.0.85:27017/");

//$collection = $mongo->MarketCollector->market_data;

/* success, error messages to be displayed */

 $messages = array(
  1=>'Record deleted successfully',
  2=>'Error occurred. Please try again',
  3=>'Record saved successfully',
  4=>'Record updated successfully',
  5=>'All fields are required' );

$pair = $_GET["pair"];
$exchange = $_GET["exchange"];
$periods = $_GET["periods"];

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

$filter = [['excange' => 'bitfinex', 'pair' => 'ltcusd'], ['CloseTime' => 1, 'ClosePrice' => 1]];

$options = [
                  'sort' => ['CloseTime' => -1],
                  'limit' => (int)$periods
];

//$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('MarketCollector.market_data', $query);

$result = array();

 foreach ($cursor as $document) {
      var_dump($document);


 }



?>
