<?php

$data = [
    'description' => 'some another concert',
    'price' => 12345,
    'tickets' => [
        [
            'barcodes' => [
                'barcode1'
            ],
        ],
        [
            'barcodes' => [
                'barcode2',
                'barcode3'
            ],
        ],
        [
            'barcodes' => [
                'barcode4',
                'barcode5',
                'barcode6',
            ],
        ],
    ],
    'userId' => 12345,
];

$str = "curl localhost:8000/endpoints/create.php -d '".json_encode($data)."' -H 'Content-Type: application/json'";

echo $str . "\n";