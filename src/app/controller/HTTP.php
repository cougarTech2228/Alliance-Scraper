<?php
/**
 * File: HTTP.php
 * User: zacharydubois
 * Date: 2016-02-05
 * Time: 19:27
 * Project: Alliance-Scraper
 */

namespace alliance;

/**
 * Class HTTP
 *
 * Used to all web interactions.
 *
 * @package alliance
 */
class HTTP {
    private
        $curl,
        $validate;

    /**
     * HTTP constructor.
     *
     * Creates CURL object and sets default options.
     *
     * @param TConfig $config
     */
    public function __construct(TConfig $config) {
        $this->curl = curl_init();
        $this->validate = array();

        curl_setopt_array($this->curl, array(
            CURLOPT_USERAGENT      => 'Alliance Scraper made by FRC Team 2228 - PHP CURL',
            CURLOPT_REFERER        => $config->read('url'),
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_RETURNTRANSFER => true
        ));
    }

    /**
     * Set URL
     *
     * Validates and sets the URL for curl.
     * Returns true when URL has successfully been set.
     *
     * @param string $url
     * @return true
     * @throws Exception
     */
    public function setURL($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("Set URL is not valid: " . $url);
        }

        if (curl_setopt($this->curl, CURLOPT_URL, $url)) {
            throw new Exception("Something weird happened when setting the URL: " . $url);
        }

        $this->validate[] = 'url';

        return true;
    }

    /**
     * Set Headers
     *
     * Sets the headers to add to the request.
     *
     * @param array $headers
     * @return true
     * @throws Exception
     */
    public function setHeaders(array $headers) {
        if (!is_array($headers)) {
            throw new Exception("Headers are not an array.");
        }

        if (!curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers)) {
            throw new Exception("Something weird happened when setting the CURL headers.");
        }

        // Headers aren't needed to everything
        //$this->validate[] = 'headers';

        return true;

    }

    /**
     * Execute Curl
     *
     * Executes curl on the set options.
     *
     * @return array
     * @throws Exception
     */
    public function exec() {
        if (!in_array('url', $this->validate)) {
            throw new Exception("Required settings for curl execute are not set.");
        }

        $headers = array();
        curl_setopt($this->curl, CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$headers) {
                unset($curl);
                $item = explode(': ', $header);
                $headers[$item[0]] = isset($item[1]) ? trim($item[1]) : null;

                return strlen($header);
            }
        );

        $r = curl_exec($this->curl);
        $code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);


        curl_close($this->curl);

        unset($this->curl);
        unset($this->validate);

        return array(
            'code'    => $code,
            'body'    => $r,
            'headers' => $headers
        );
    }

}
