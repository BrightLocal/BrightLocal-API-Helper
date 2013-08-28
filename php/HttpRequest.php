<?php
namespace BrightLocal;

/**
 * Class HttpRequest
 * @package BrightLocal
 */
class HttpRequest {
    /**
     * @var array
     */
    protected static $baseOptions = array(
        CURLOPT_HEADER            => 0,
        CURLOPT_RETURNTRANSFER    => 1,
        CURLOPT_FOLLOWLOCATION    => 1
    );
    /**
     * @var int
     */
    protected static $lastHttpCode;

    /**
     * @param $url
     * @param $params
     * @return bool|mixed
     */
    public static function post($url, $params = array()) {
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_HEADER            => 0,
            CURLOPT_RETURNTRANSFER    => 1,
            CURLOPT_FOLLOWLOCATION    => 1,
            CURLOPT_TIMEOUT           => 30, // 30 seconds
            CURLOPT_CONNECTTIMEOUT    => 10, // 10 seconds
            CURLOPT_DNS_CACHE_TIMEOUT => 86400, // 1 day
            CURLOPT_POSTFIELDS       => http_build_query($params),
            CURLOPT_POST             => true
        ));
        $result = curl_exec($curl);
        static::$lastHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return in_array(static::$lastHttpCode, array(200, 403, 503)) ? json_decode($result, true) : false;
    }
}