<?php

require_once __DIR__ . "/vendor/autoload.php";

$mongo = new MongoDB\Client("mongodb://172.16.0.85:27017/");

$collection = $mongo->MarketCollector->market_data;
$cursor2 = $collection->find($query);

$pair = $_GET["pair"];
$exchange = $_GET["exchange"];
$periods = $_GET["periods"];


$query = '({
    "exchange": "'.$exchange.'",
    "pair": "'.$pair.'"
}).sort({
    "CloseTime": -1
}).limit('.$periods.');';

$cursor2 = $collection->find($query);

print_r($cursor2);


?>
