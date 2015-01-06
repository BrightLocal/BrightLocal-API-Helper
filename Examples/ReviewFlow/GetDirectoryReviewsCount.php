<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$counts = $api->get('/v4/rf/reviews/directory/count', [
    'report-id'  => 1,
    'directory'  => 'google'
]);
print_r($counts);
