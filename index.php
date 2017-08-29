<?php

require_once __DIR__ . "/vendor/autoload.php";

//$manager = new MongoDB\Driver\Manager("mongodb://172.16.0.85:27017/");


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

$filter = array(
    "excange" => $exchange,
    "pair" => $pair
);
$options = array(
    "projection" => array(
        "CloseTime" => 1,
        "CloseValue" => 1,
    ),
    "sort" => array(
        "CloseTime" => -1,
    )

);

$readPreference = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY);
$query = new MongoDB\Driver\Query($filter, $options);
$manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017/");
$result = $manager->executeQuery("MarketCollector.market_data", $query, $readPreference);

foreach($result as $document) {
    print_r($document);
}





?>
