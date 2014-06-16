<?php
namespace BrightLocal\Batches;

use BrightLocal\Api;

/**
 * Class V1
 * @package BrightLocal
 */
class V1 {
    /** @var \BrightLocal\Api */
    protected $api;

    /**
     * @param Api $api
     */
    public function __construct(Api $api) {
        $this->api = $api;
    }

    /**
     * @param string $customerRef
     * @return bool|string
     */
    public function create($customerRef = '') {
        $results = $this->api->call('/v1/create-batch-id', array(
            'customer-ref' => $customerRef
        ));
        return $results['response']['status'] === 'added' ? $results['response']['batch-id'] : false;
    }

    /**
     * @param string $batchId
     * @return bool
     */
    public function commit($batchId) {
        $results = $this->api->call('/v1/commit-batch', array(
            'batch-id' => $batchId
        ));
        return $results['response']['status'] === 'Committed';
    }

    /**
     * @param string $batchId
     * @return bool|mixed
     */
    public function get_results($batchId) {
        return $this->api->call('/v1/get-batch-results', array(
            'batch-id' => $batchId
        ));
    }
}
