<?php

$data = [
    'id' => 123
];

//curl localhost:8000/endpoints/authenticate.php -d '{"id":123}' -H 'Content-Type: application/json'
$str = "curl localhost:8000/endpoints/authenticate.php -d '".json_encode($data)."' -H 'Content-Type: application/json'";

echo $str . "\n";