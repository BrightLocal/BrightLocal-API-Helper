<?php
require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;
use BrightLocal\Batches\V4 as BatchApi;

$directories = array('google', 'citysearch', 'dexknows', 'kudzu', 'manta');

// setup API wrappers
$api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
$batchApi = new BatchApi($api);

// Step 1: Create a new batch
$result = $batchApi->create();

if ($result['success']) {
    $batchId = $result['batch-id'];

    printf('Created batch ID %d%s', $batchId, PHP_EOL);

    // Step 2: Add directory jobs to batch
    foreach ($directories as $directory) {
        $result = $api->call('/v4/ld/fetch-profile-url', array(
            'batch-id'        => $batchId,
            'local-directory' => $directory,
            'business-names'  => 'Eleven Madison Park',
            'country'         => 'USA',
            'city'            => 'New York',
            'postcode'        => '10010'
        ));
        if ($result['success']) {
            printf('Added job with ID %d%s', $result['job-id'], PHP_EOL);
        }
    }

    // Step 3: Commit batch (to signal all jobs added, processing starts)
    $result = $batchApi->commit($batchId);

    if ($result['success']) {
        echo 'Committed batch successfully.'.PHP_EOL;
    }
}
