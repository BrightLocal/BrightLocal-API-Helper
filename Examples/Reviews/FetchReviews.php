<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;
use BrightLocal\Batches\V4 as BatchApi;

$profileUrls = array(
    'https://plus.google.com/114222978585544488148/about?hl=en',
    'https://plus.google.com/117313296997732479889/about?hl=en',
    'https://plus.google.com/111550668382222753542/about?hl=en'
);

// setup API wrappers
$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$batchApi = new BatchApi($api);

// Step 1: Create a new batch
$batchId = $batchApi->create();

if ($batchId) {
    printf('Created batch ID %d%s', $batchId, PHP_EOL);

    // Step 2: Add review lookup jobs to batch
    foreach ($profileUrls as $profileUrl) {
        $result = $api->call('/v4/ld/fetch-reviews', array(
            'batch-id'    => $batchId,
            'profile-url' => $profileUrl,
            'country'     => 'USA'
        ));
        if ($result['success']) {
            printf('Added job with ID %d%s', $result['job-id'], PHP_EOL);
        }
    }

    // Step 3: Commit batch (to signal all jobs added, processing starts)
    if ($batchApi->commit($batchId)) {
        echo 'Committed batch successfully.'.PHP_EOL;
    }
}
