<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$directories = $api->get('/v4/rf/directories', [
    'report-id'  => 1,
    'start-date' => null
]);
print_r($directories);
