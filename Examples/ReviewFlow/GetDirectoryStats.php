<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$stats = $api->get('/v4/rf/1/directories/stats');
print_r($stats);
