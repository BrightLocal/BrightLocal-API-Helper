<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$success = $api->post('/v4/rf/add', [
    'report-name'            => 'Le Bernardin',
    'client-id'              => 0,
    'business-name'          => 'Le Bernardin',
    'contact-telephone'      => '+1 212-554-1515',
    'address1'               => '155 West 51st Street',
    'address2'               => '',
    'city'                   => 'New York',
    'postcode'               => '10019',
    'country'                => 'USA', // 3 letter iso code
    'schedule'               => 'M', // D (Daily), W (Weekly) or M (Monthly)
    'run-on'                 => 1, // Day of week or day of month
    'receive-email-alerts'   => 0, // 1 or 0
    'alert-email-addresses'  => '["user1@test.com","user2@test.com","user3@test.com"]',
    'white-label-profile-id' => null,
    'is-public'              => 1 // 1 or 0
]);
print_r($success);
