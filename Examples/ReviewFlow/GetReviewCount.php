<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$count = $api->get('/v4/rf/reviews/all/count', [
    'report-id'  => 1,
    'start-date' => '2014-01-01'
]);
print_r($count);
