<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$growth = $api->get('/v4/rf/1/reviews/growth');
print_r($growth);
