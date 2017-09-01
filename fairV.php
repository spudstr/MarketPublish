<?php

require_once __DIR__ . "/vendor/autoload.php";
header('Content-Type: application/json');
//$manager = new MongoDB\Driver\Manager("mongodb://172.16.0.85:27017/");


$pair = strtoupper($_GET["pair"]);
$exchange = strtoupper($_GET["exchange"]);
$periods = $_GET["periods"];



$aggregateQuery = '';


//print_r($aggregateQuery);



/*
$readPreference = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY);
//$query = new MongoDB\Driver\Query($filter, $options);
$manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017/");
$query = new MongoDB\Driver\Command($aggregateQuery);

$result = $manager->executeQuery("MarketCollector.bitfinex_ticker", $query, $readPreference);
*/

$readPreference = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY);
//$query = new MongoDB\Driver\Query($filter, $options);
$manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017/");
$query = new MongoDB\Driver\Command([
'aggregate' => 'bitfinex_ticker',
 'pipeline' => [
    ['$match' => ['pair' => $pair]],
    ['$group' => ['_id' => ['year' => ['$year' => '$timestamp'],'month' => ['$month' => '$timestamp'],'day' => ['$dayOfMonth' => '$timestamp'],'day2' => ['$dayOfYear' => '$timestamp'],'hour' => ['$hour' => '$timestamp'],'minute_interval' => ['$subtract' => [['$minute' => '$timestamp'],['$mod' => [['$minute' => '$timestamp'], 1]]]]],'TopAsk' => ['$min' => '$ASK'],'TopBid' => ['$max' => '$BID'],'timestamp' => ['$first' => '$timestamp']]],
    ['$project' => ['_id' => 0,'TopAsk' => 1,'TopBid' => 1,'ASK' => 1,'BID' => 1,'FairV' => ['$divide' => [['$add' => ['$TopAsk', '$TopBid']], 2]],'timestamp' => 1,'tz' => 1]],
    ['$sort' => ['timestamp' => -1]],
    ['$limit' => (int)$periods]
  ],
  'cursor' => new stdClass,
]);
$cursor = $manager->executeCommand('MarketCollector',$query);

$final = array();
$dataArray = array();

  foreach ($cursor as $data)
  {

         $tmpObject = $data->timestamp;
          $ts = get_object_vars($tmpObject);

         $dataArray["result"][$periods][] = array('TopAsk' => $data->TopAsk,'TopBid' => $data->TopBid,"FairValue" => $data->FairV,'Timestamp' => (int)$ts["milliseconds"],'PAIR'=> $pair);


  }


echo json_encode($dataArray,JSON_PRETTY_PRINT);

?>
