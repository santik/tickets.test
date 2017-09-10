<?php

$data = [
    'id' => 123
];

$str = "curl localhost:8000/endpoints/authenticate.php -d '".json_encode($data)."' -H 'Content-Type: application/json'";

echo $str . "\n";