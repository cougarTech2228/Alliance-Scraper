<?php
/**
 * File: BlueAlliance.php
 * User: zacharydubois
 * Date: 2016-02-05
 * Time: 19:49
 * Project: Alliance-Scraper
 */

namespace alliance;


/**
 * Class BlueAlliance
 *
 * Handles the Blue Alliance API.
 *
 * @package alliance
 */
class BlueAlliance {
    private
        $TConfig;

    const uri = 'https://www.thebluealliance.com/api/v2';

    /**
     * BlueAlliance constructor.
     *
     * Sets TConfig object for later use.
     *
     * @param TConfig $TConfig
     */
    public function __construct(TConfig $TConfig) {
        $this->TConfig = $TConfig;
    }

    /**
     * Get Events for a Year
     *
     * Returns and array of events for the input year.
     *
     * @param int $year
     * @return array|false
     * @throws Exception
     */
    public function getEvents($year) {
        if (!is_int($year)) {
            throw new Exception("Year is not integer: " . $year);
        }

        if ($year > date('Y') + 1 || $year < 1992) {
            throw new Exception("Invalid year (not in allowed range).");
        }

        $request = new HTTP($this->TConfig);
        $request->setURL(static::uri . '/events/' . $year);
        $request->setHeaders($this->setAPIHeaders());
        $out = $request->exec();

        if ($out['code'] !== 200) {
            throw new Exception("API send non-200 response: " . $out['code'] . " " . $out['headers']);
        }

        $body = json_decode($out['body'], true);

        if (!is_array($body)) {
            throw new Exception("getEvents(...) request body was not array when attempted to be decoded.");
        }

        if (count($body) > 0) {
            $eventList = array();

            foreach ($body as $event) {
                $eventList[$event['key']] = array(
                    'name' => $event['name'],
                    'type' => $event['event_type_string']
                );
            }

            unset($body);
            unset($out);
            unset($request);

            return $eventList;
        }

        return false;
    }

    /**
     * Set API Headers
     *
     * Sets the required headers for The Blue Alliance API.
     *
     * @return array
     * @throws Exception
     */
    private function setAPIHeaders() {
        $team = $this->TConfig->read('id');
        $url = $this->TConfig->read('url');

        // Format ID:Desc:Ver
        $header = array(
            'X-TBA-App-Id' => $team . ':' . 'Alliance Scrapper made by FRC Team 2228 being used by ' . $url . ':' . 'inf'
        );

        return $header;
    }
}