<?php

$data = [
    'description' => 'some another concert',
    'price' => 12345,
    'tickets' => [
        [
            'barcodes' => [
                'barcode111'
            ],
        ],
        [
            'barcodes' => [
                'barcode111',
                'barcode31'
            ],
        ],
        [
            'barcodes' => [
                'barcode41',
                'barcode51',
                'barcode61',
            ],
        ],
    ],
    'userId' => 12345,
];
//curl localhost:8000/endpoints/create.php -d '{"description":"some concert","price":12345,"tickets":[{"barcodes":["barcode1"]},{"barcodes":["barcode2","barcode3"]},{"barcodes":["barcode4","barcode5","barcode6"]}],"userId":12345}' -H 'Content-Type: application/json'
$str = "curl localhost:8000/endpoints/create.php -d '".json_encode($data)."' -H 'Content-Type: application/json'";

echo $str . "\n";