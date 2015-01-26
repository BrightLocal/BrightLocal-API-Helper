<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$reports = $api->get('/v4/rf', [
    'client-id' => null // optionally set to filter list by a specific client ID
]);
print_r($reports);
