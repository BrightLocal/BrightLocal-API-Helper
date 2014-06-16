<?php
namespace BrightLocal\Batches;

use BrightLocal\Api;

/**
 * Class V4
 * @package BrightLocal
 */
class V4 {
    /** @var \BrightLocal\Api */
    protected $api;

    /**
     * @param Api $api
     */
    public function __construct(Api $api) {
        $this->api = $api;
    }

    /**
     * @param bool $stopOnJobError
     * @return bool|int
     */
    public function create($stopOnJobError = false) {
        $result = $this->api->call('/v4/batch', array(
            'stop-on-job-error' => (int) $stopOnJobError
        ));
        return $result['success'] ? $result['batch-id'] : false;
    }

    /**
     * @param int $batchId
     * @return bool
     */
    public function commit($batchId) {
        $result = $this->api->call('/v4/batch', array(
            'batch-id' => $batchId
        ), Api::HTTP_METHOD_PUT);
        return $result['success'];
    }

    /**
     * @param int $batchId
     * @return mixed
     */
    public function get_results($batchId) {
        return $this->api->call('/v4/batch', array(
            'batch-id' => $batchId
        ), Api::HTTP_METHOD_GET);
    }

    /**
     * @param int $batchId
     * @return bool
     */
    public function delete($batchId) {
        $results = $this->api->call('/v4/batch', array(
            'batch-id' => $batchId
        ), Api::HTTP_METHOD_DELETE);
        return $results['success'];
    }
}
