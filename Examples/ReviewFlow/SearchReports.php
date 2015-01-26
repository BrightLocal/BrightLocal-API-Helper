<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$reports = $api->get('/v4/rf/search', [
    'q' => 'Le Bernardin'
]);
print_r($reports);
