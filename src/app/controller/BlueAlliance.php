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
            var_dump($out);
            throw new Exception("API sent non-200 response: " . $out['code'] . " " . $out['headers']);
        }

        $body = json_decode($out['body'], true);

        if (!is_array($body)) {
            throw new Exception("getEvents(...) request body was not array when attempted to be decoded.");
        }

        if (count($body) > 0) {
            $eventList = array();

            foreach ($body as $event) {
                $eventList[] = array(
                    'key'  => $event['key'],
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
     * Get Rankings for Event Key
     *
     * Gets the rankings for a specified event key.
     *
     * @param string $eventKey
     * @return array|false
     * @throws Exception
     */
    public function getRankings($eventKey) {
        if (!is_string($eventKey)) {
            throw new Exception("eventKey is not string: " . $eventKey);
        }

        $request = new HTTP($this->TConfig);
        $request->setURL(static::uri . '/event/' . $eventKey . '/rankings');
        $request->setHeaders($this->setAPIHeaders());
        $out = $request->exec();

        if ($out['code'] !== 200) {
            throw new Exception("Event key rankings returned non-200 response code: " . $out['code']);
        }

        $body = json_decode($out['body'], true);

        if (!is_array($body)) {
            throw new Exception("getRankings(...) request body was not array when attempted to be decoded.");
        }

        if (count($body) > 0) {
            $rankings = array();

            // Remove the header
            unset($body[0]);

            foreach ($body as $row) {
                $rankings[] = array(
                    'rank' => $row['0'],
                    'team' => $row['1']
                );
            }

            unset($body);
            unset($out);
            unset($request);

            return $rankings;
        }

        return false;
    }

    /**
     * Get OPRs for Given Event
     *
     * Returns array of keys (team) and values (OPR) for the given event.
     *
     * @param string $eventKey
     * @return array|bool
     * @throws Exception
     */
    public function getOPRs($eventKey) {
        if (!is_string($eventKey)) {
            throw new Exception("eventKey is not string: " . $eventKey);
        }

        $request = new HTTP($this->TConfig);
        $request->setURL(static::uri . '/event/' . $eventKey . '/stats');
        $request->setHeaders($this->setAPIHeaders());
        $out = $request->exec();

        if ($out['code'] !== 200) {
            throw new Exception("Event key stats returned non-200 response code: " . $out['code']);
        }

        $body = json_decode($out['body'], true);

        if (!is_array($body)) {
            //throw new Exception("getOPRs(...) request body was not array when attempted to be decoded.");
            return false;
        }

        if (array_key_exists('oprs', $body) && count($body['oprs']) > 0) {
            $oprs = array();

            foreach ($body['oprs'] as $team => $opr) {
                $oprs[$team] = $opr;
            }

            unset($body);
            unset($out);
            unset($request);

            return $oprs;
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

        // Format ID:Desc:Ver
        $header = array(
            'X-TBA-App-Id: ' . $team . ':' . 'Alliance_Scrapper_made_by_FRC_Team_2228' . ':' . 'inf'
        );

        return $header;
    }
}