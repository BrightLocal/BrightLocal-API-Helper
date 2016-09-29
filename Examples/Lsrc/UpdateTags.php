<?php
define('UPDATES_FILE', __DIR__ . '/UpdateTags.csv');

require '../Auth.php';
require '../../vendor/autoload.php';

use BrightLocal\Api;

if ($csv = fopen(UPDATES_FILE, 'r')) {
    $api = new Api(API_KEY, API_SECRET, API_ENDPOINT);
    fgetcsv($csv); // skip header
    while (($entry = fgetcsv($csv)) !== false) {
        if (count($entry) === 2) {
            $campaignId = (int) $entry[0];
            // tidy up tags and remove duplicates
            $tags = explode('|', trim($entry[1]));
            array_walk($tags, function(&$tag) {
                $tag = trim($tag);
            });
            $tags = array_unique($tags);
            $details = $api->post('/v2/lsrc/update', [
                'campaign-id' => $campaignId,
                'tags'        => implode(',', $tags),
            ]);
            $statusPrefix = $details['response']['status'] === 'Report Updated' ? 'Successfully updated' : 'Unable to update';
            printf('%s tags for campaign ID %d...%s', $statusPrefix, $campaignId, PHP_EOL);
        } else {
            printf('Entry contains the wrong number of fields...%s', PHP_EOL);
        }
    }
    fclose($csv);
}
