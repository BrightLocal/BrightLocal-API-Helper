<?php
namespace BrightLocal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
    public function __construct($apiKey, $apiSecret, $endpoint = '') {
        $this->endpoint = empty($endpoint) ? static::ENDPOINT : $endpoint;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * @return array
     */
    public function get_sig_and_expires() {
        $expires = (int) gmdate('U') + static::MAX_EXPIRY;
        $sig = base64_encode(hash_hmac('sha1', $this->apiKey . $expires, $this->apiSecret, true));
        return array($sig, $expires);
    }

    /**
     * @param string $method
     * @param array $params
     * @param string $httpMethod
     * @throws \Exception
     * @return bool|mixed
     */
    public function call($method, $params = [], $httpMethod = self::HTTP_METHOD_POST) {
        if (!in_array($httpMethod, $this->allowedHttpMethods)) {
            throw new \Exception('Invalid HTTP method specified.');
        }
        $method = str_replace('/seo-tools/api', '', $method);
        // some methods only require api key but there's no harm in also sending
        // sig and expires to those methods
        list($sig, $expires) = $this->get_sig_and_expires();
        $params = array_merge([
            'api-key' => $this->apiKey,
            'sig'     => $sig,
            'expires' => $expires
        ], $params);
        $client = new Client;
        try {
            $result = $client->$httpMethod($this->endpoint . '/' . ltrim($method, '/'), $this->get_options($httpMethod, $params));
        } catch (RequestException $e) {
            $result = $e->getResponse();
        }
        $this->lastHttpCode = $result->getStatusCode();
        $content = $result->getBody()->getContents();
        $result->getBody()->close();
        return \GuzzleHttp\json_decode($content, true);
    }

    /**
     * @param string $method
     * @param array $params
     * @return bool|mixed
     */
    public function get($method, $params = array()) {
        return $this->call($method, $params, static::HTTP_METHOD_GET);
    }

    /**
     * @param string $method
     * @param array $params
     * @return bool|mixed
     */
    public function post($method, $params = array()) {
        return $this->call($method, $params, static::HTTP_METHOD_POST);
    }

    /**
     * @param string $method
     * @param array $params
     * @return bool|mixed
     */
    public function put($method, $params = array()) {
        return $this->call($method, $params, static::HTTP_METHOD_PUT);
    }

    /**
     * @param string $method
     * @param array $params
     * @return bool|mixed
     */
    public function delete($method, $params = array()) {
        return $this->call($method, $params, static::HTTP_METHOD_DELETE);
    }

    /**
     * @return int
     */
    public function get_last_http_code() {
        return $this->lastHttpCode;
    }

    /**
     * @param $httpMethod
     * @param array $params
     * @return array
     */
    private function get_options($httpMethod, $params) {
        if ($httpMethod === static::HTTP_METHOD_GET) {
            return ['query' => $params];
        }
        foreach ($params as $param) {
            if (is_resource($param)) {
                return ['multipart' => $this->convert_to_multipart($params)];
            }
        }
        return ['form_params' => $params];
    }

    /**
     * @param array $params
     * @return array
     */
    private function convert_to_multipart($params) {
        $multipart = [];
        foreach ($params as $key => $value) {
            $multipart[] = [
                'name'     => $key,
                'contents' => $value,
            ];
        }
        return $multipart;
    }
}
