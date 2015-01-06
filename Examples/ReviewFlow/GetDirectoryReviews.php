<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$reviews = $api->get('/v4/rf/reviews/directory', [
    'report-id'  => 1,
    'directory'  => 'google',
    'start-date' => '',
    'offset'     => 0,
    'limit'      => 20
]);
print_r($reviews);
