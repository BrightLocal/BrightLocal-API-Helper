<?php
namespace BrightLocal;

/**
 * Class BatchApiV4
 * @package BrightLocal
 */
class BatchApiV4 {
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
     * @return bool|mixed
     */
    public function create($stopOnJobError = false) {
        return $this->api->call('/v4/batch', array(
            'stop-on-job-error' => (int) $stopOnJobError
        ));
    }

    /**
     * @param int $batchId
     * @return bool|mixed
     */
    public function commit($batchId) {
        return $this->api->call('/v4/batch', array(
            'batch-id' => $batchId
        ), Api::HTTP_METHOD_PUT);
    }

    /**
     * @param int $batchId
     * @return bool|mixed
     */
    public function get_results($batchId) {
        return $this->api->call('/v4/batch', array(
            'batch-id' => $batchId
        ), Api::HTTP_METHOD_GET);
    }

    /**
     * @param int $batchId
     * @return bool|mixed
     */
    public function delete($batchId) {
        return $this->api->call('/v4/batch', array(
            'batch-id' => $batchId
        ), Api::HTTP_METHOD_DELETE);
    }
}
