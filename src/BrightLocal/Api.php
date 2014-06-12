<?php
namespace BrightLocal;

/**
 * Class Api
 *
 * @package BrightLocal
 */
class Api {

    /** API endpoint URL */
    const ENDPOINT = 'https://tools.brightlocal.com/seo-tools/api';
    /** expiry can't be more than 30 minutes (1800 seconds) */
    const MAX_EXPIRY = 1800;
    const HTTP_METHOD_POST = 'post';
    const HTTP_METHOD_GET = 'get';
    const HTTP_METHOD_PUT = 'put';
    const HTTP_METHOD_DELETE = 'delete';

    /** @var string */
    protected $endpoint;
    /** @var string */
    protected $apiKey;
    /** @var string */
    protected $apiSecret;
    /** @var int */
    protected $lastHttpCode;
    /** @var array */
    protected $allowedHttpMethods = array(
        self::HTTP_METHOD_POST,
        self::HTTP_METHOD_GET,
        self::HTTP_METHOD_PUT,
        self::HTTP_METHOD_DELETE
    );

    /**
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $endpoint
     */
    public function __construct($apiKey, $apiSecret, $endpoint = self::ENDPOINT) {
        $this->endpoint = $endpoint;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * @return array
     */
    public function get_sig_and_expires() {
        $expires = (int) gmdate('U') + self::MAX_EXPIRY;
        $sig = base64_encode(hash_hmac('sha1', $this->apiKey . $expires, $this->apiSecret, true));
        return array($sig, $expires);
    }

    /**
     * @param string $method
     * @param array $params
     * @return bool|mixed
     */
    public function call($method, $params = array(), $httpMethod = self::HTTP_METHOD_POST) {
        if (in_array($this->allowedHttpMethods, $httpMethod)) {
            throw new Exception('Invalid HTTP method specified.');
        }
        $method = str_replace('/seo-tools/api', '', $method);
        // some methods only require api key but there's no harm in also sending
        // sig and expires to those methods
        list($sig, $expires) = $this->get_sig_and_expires();
        $params = array_merge(array(
            'api-key' => $this->apiKey,
            'sig'     => $sig,
            'expires' => $expires
        ), $params);
        $client = new GuzzleHttp\Client;
        $result = $client->$httpMethod($this->endPoint . $method, $params);
        $this->lastHttpCode = $result->getStatusCode();
        return $result->json();
    }

    /**
     * @return int
     */
    public function get_last_http_code() {
        return $this->lastHttpCode;
    }
}
