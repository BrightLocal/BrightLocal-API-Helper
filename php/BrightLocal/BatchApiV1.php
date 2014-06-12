<?php
namespace BrightLocal;

/**
 * Class BatchApiV1
 * @package BrightLocal
 */
class BatchApiV1 {
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
     * @return bool|mixed
     */
    public function create($customerRef = '') {
        return $this->api->call('/v1/create-batch-id', array(
            'customer-ref' => $customerRef
        ));
    }

    /**
     * @param string $batchId
     * @return bool|mixed
     */
    public function commit($batchId) {
        return $this->api->call('/v1/commit-batch', array(
            'batch-id' => $batchId
        ));
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
