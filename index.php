<?php

require_once __DIR__ . "/vendor/autoload.php";

$mongo = new MongoDB\Client("mongodb://172.16.0.85:27017/");

$collection = $mongo->MarketCollector->market_data;


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

$where_fields = array(
            'exchange' => $exchange,
            'pair' => $pair
        );
$options = array(
                        'sort' => array(
                                        'CloseTime' => -1
                                    ),
                        'limit' => (int)$periods
                    );

$cursor2 = $collection->find($where_fields, $options);


$results = $cursor2->toArray();
print_r($results);


?>
