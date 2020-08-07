<?php

namespace Convee\Core;

/**
 * Curl Http Tools
 */
class Curl
{
    private $ch = null; // curl handle
    private $headers = [];// request header
    private $proxy = null; // http proxy
    private $timeout = 5;    // connect timeout
    private $httpParams = null;


    public function __construct()
    {
        $this->ch = curl_init();
    }

    /**
     * set http header
     * @param $header
     * @return $this
     */
    public function setHeader($header)
    {
        if (is_array($header)) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
        }
        $this->headers = $header;
        return $this;
    }

    /**
     * http timeout
     * @param int $time
     * @return $this
     */
    public function setTimeout($time)
    {
        if ($time <= 0) {
            $time = 5;
        }
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $time);
        return $this;
    }


    /**
     * http proxy
     * @param string $proxy
     * @return $this
     */
    public function setProxy($proxy)
    {
        if ($proxy) {
            curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
        }
        return $this;
    }

    /**
     * http port
     * @param int $port
     * @return $this
     */
    public function setProxyPort($port)
    {
        if (is_int($port)) {
            curl_setopt($this->ch, CURLOPT_PROXYPORT, $port);
        }
        return $this;
    }

    /**
     * set referer
     * @param string $referer
     * @return $this
     */
    public function setReferer($referer = "")
    {
        if (!empty($referer)) {
            curl_setopt($this->ch, CURLOPT_REFERER, $referer);
        }
        return $this;
    }

    /**
     * user agent
     * @param string $agent
     * @return $this
     */
    public function setUserAgent($agent = "")
    {
        if ($agent) {
            curl_setopt($this->ch, CURLOPT_USERAGENT, $agent);
        }
        return $this;
    }

    /**
     * show http header
     * @param $show
     * @return $this
     */
    public function showResponseHeader($show)
    {
        curl_setopt($this->ch, CURLOPT_HEADER, $show);
        return $this;
    }


    /**
     * set params
     * @param array|string $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->httpParams = $params;
        return $this;
    }

    /**
     * set ca info
     * @param $file
     */
    public function setCaInfo($file)
    {
        curl_setopt($this->ch, CURLOPT_CAINFO, $file);
    }


    /**
     * get
     * @param string $url
     * @param string $dataType
     * @return bool|mixed
     */
    public function get($url, $dataType = 'text')
    {
        if (stripos($url, 'https://') !== false) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($this->ch, CURLOPT_SSLVERSION, 1);
        }
        // 设置get参数
        if (!empty($this->httpParams) && is_array($this->httpParams)) {
            if (strpos($url, '?') !== false) {
                $url .= '&' . http_build_query($this->httpParams);
            } else {
                $url .= '?' . http_build_query($this->httpParams);
            }
        }
        // end 设置get参数
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($this->ch);
        $status = curl_getinfo($this->ch);
        curl_close($this->ch);
        if (isset($status['http_code']) && $status['http_code'] == 200) {
            if ($dataType == 'json') {
                $content = json_decode($content, true);
            }
            return $content;
        }
        return false;
    }


    /**
     * post
     * @param $url
     * @param string $dataType
     * @return mixed|bool|string
     */
    public function post($url, $dataType = 'text')
    {
        if (stripos($url, 'https://') !== false) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($this->ch, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($this->ch, CURLOPT_URL, $url);
        // 设置post body
        if (is_array($this->httpParams)) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($this->httpParams));
        } else {
            if (is_string($this->httpParams)) {
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->httpParams);
            }
        }
        // end 设置post body
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POST, true);
        $content = curl_exec($this->ch);
        $status = curl_getinfo($this->ch);
        curl_close($this->ch);
        if (isset($status['http_code']) && $status['http_code'] == 200) {
            if ($dataType == 'json') {
                $content = json_decode($content, true);
            }
            return $content;
        }
        return false;
    }
}