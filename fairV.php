<?php

require_once __DIR__ . "/vendor/autoload.php";

//$manager = new MongoDB\Driver\Manager("mongodb://172.16.0.85:27017/");


$pair = $_GET["pair"];
$exchange = $_GET["exchange"];
$periods = $_GET["periods"];



$aggregateQuery = '[
[
  $match => [
    pair => \'LTCUSD\'
  ]
],
[
  $group => [
    "_id" => [
      "year" => [
        "$year" => "$timestamp"
      ],
      "month"=> [
        "$month" => "$timestamp"
      ],
      "day" => [
        "$dayOfMonth" => "$timestamp"
      ],
      "day2" => [
        "$dayOfYear" => "$timestamp"
      ],
      "hour" => [
        "$hour" => "$timestamp"
      ],
      "minute_interval" => [
        "$subtract" => [[
            "$minute" => "$timestamp"
          ],
          [
            "$mod"=> [[
              "$minute" => "$timestamp"
            ], 1]
          ]
        ]
      ]
    ],
    "TopAsk" => [
      "$min" => "$ASK"
    ],
    "TopBid" => [
      "$max" => "$BID"
    ],
    "timestamp" => [
      "$first" => "$timestamp"
    ]
  ]
],
[
  $project => [
    _id => 0,
    TopAs k=> 1,
    TopBid => 1,
    ASK => 1,
    BID => 1,
    FairV=> [
      $divide=> [[
        $add => ["$TopAsk", "$TopBid"]
      ], 2]
    ],
    timestamp => 1,
    tz => 1
  ]
],
[
  $sort=> [
    timestamp => -1
  ]
],
[
  $limit => 500
],
]';


print_r($aggregateQuery);




$readPreference = new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY);
$query = new MongoDB\Driver\Query($filter, $options);
$manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017/");
//$result = $manager->executeQuery("MarketCollector.market_data", $query, $readPreference);
$agg = $manager->MarketCollector->Bitfinex_ticker->aggregate($aggregateQuery);
var_dump($agg);




?>
