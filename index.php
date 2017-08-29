<?php

require_once __DIR__ . "/vendor/autoload.php";

$collection = new MongoDB\Client("mongodb://172.16.0.85:27017/");

$pair = $_GET["pair"];
$exchange = $_GET["exchange"];
$periods = $_GET["periods"];

$query = 'db.market_data.find({
    "exchange": "'.$exchange.'",
    "pair": "'.$pair.'"
}).sort({
    "CloseTime": -1
}).limit('.$periods.');';

echo $query;

?>
