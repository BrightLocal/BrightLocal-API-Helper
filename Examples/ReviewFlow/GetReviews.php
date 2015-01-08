<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$reviews = $api->get('/v4/rf/reviews', [
    'report-id' => 1,
    'offset'    => 0,
    'limit'     => 20,
    'sort'      => 'asc', // asc or desc
    'directory' => '', // optionally filter by directory
    'stars'     => '' // optionally filter by star rating
]);
print_r($reviews);
