<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$success = $api->put('/v4/rf/1', [
    'report-name'            => 'Le Bernardin',
    'schedule'               => 'W', // D (Daily), W (Weekly) or M (Monthly)
    'run-on'                 => 3, // Day of week or day of month
    'directories'            => json_encode([
        'yelp' => [
            'url'     => 'http://www.yelp.com/biz/le-bernardin-new-york',
            'include' => 1
        ]
    ])
]);
print_r($success);
