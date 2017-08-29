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

$where_fields =  "[
                                'exchange' => $exchange,
                                'pair' => $pair
                  ],
                  ['projection' => [
                                    'CloseTime' => 1,
                                    'ClosePrice' => 1
                                    ]
                   ]";
                  
$options = array(
                        'sort' => array(
                                        'CloseTime' => -1
                                    ),
                        'limit' => (int)$periods
                    );

$cursor2 = $collection->find($where_fields, $options);

var_dump($cursor2);

$results = $cursor2->toArray();
$results_json = json_encode($results, JSON_PRETTY_PRINT);

print_r($results_json);


?>
