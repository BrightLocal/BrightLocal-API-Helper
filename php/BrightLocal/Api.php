<?php
namespace BrightLocal;

require 'HttpRequest.php';
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
    /** @var string */
    protected $endpoint;
    /** @var string */
    protected $apiKey;
    /** @var string */
    protected $apiSecret;

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
        $expires = time() + self::MAX_EXPIRY;
        $sig = base64_encode(hash_hmac('sha1', $this->apiKey . $expires, $this->apiSecret, true));
        return array($sig, $expires);
    }

    /**
     * @param string $method
     * @param array $params
     * @return bool|mixed
     */
    public function call($method, $params = array()) {
        $method = str_replace('/seo-tools/api', '', $method);
        // some methods only require api key but there's no harm in also sending
        // sig and expires to those methods
        list($sig, $expires) = $this->get_sig_and_expires();
        $params = array_merge(array(
            'api-key' => $this->apiKey,
            'sig'     => $sig,
            'expires' => $expires
        ), $params);
        // some methods support get but all support post so we can
        // send all requests as post
        return HttpRequest::post($this->endpoint . $method, $params);
    }
}
