<?php
define('API_KEY', '<API KEY HERE>');
define('API_SECRET', '<API SECRET HERE>');

require 'vendor/autoload.php';

use BrightLocal\Api;
use BrightLocal\Batches\V4 as BatchApi;

// setup API wrappers
$api = new Api(API_KEY, API_SECRET);
$batchApi = new BatchApi($api);
print_r($batchApi->get_results($argv[1]));
