<?php
/**
 * File: App.php
 * User: zacharydubois
 * Date: 2016-02-05
 * Time: 23:24
 * Project: Alliance-Scraper
 */

namespace alliance;


/**
 * Class App
 *
 * Handles running the app.
 *
 * @package alliance
 */
class App {
    private
        $TConfig;

    /**
     * App constructor.
     *
     * Initializes TConfig.
     */
    public function __construct() {
        $this->TConfig = new TConfig();
    }

    /**
     * Run
     *
     * Runs the application.
     *
     * @param array $args
     * @return true
     * @throws Exception
     */
    public function run(array $args) {
        if (!isset($args[1]) || !isset($args[2])) {
            throw new Exception("Invalid input.");
        }

        $command = $args[1];
        $param = $args[2];

        if (count($args) > 4) {
            throw new Exception("Expected 2-3 params, received: " . count($args));
        }

        switch ($command) {
            case 'year':
                $param = (int)$param;
                $out = $this->commandYear($param);
                break;
            case 'rankings':
                $out = $this->commandRankings($param);
                break;
            default:
                throw new Exception("Unknown command: " . $command);
                break;
        }

        echo $out . PHP_EOL;

        if (isset($args[3])) {
            echo "Saving to " . $args[3] . PHP_EOL;

            if (!File::write($args[3], $out)) {
                throw new Exception("File failed to write.");
            }
        }

        return true;
    }

    /**
     * Year Command
     *
     * Creates a CSV table for the events in that year.
     *
     * @param int $year
     * @return string
     * @throws Exception
     */
    private function commandYear($year) {
        if (!is_int($year)) {
            throw new Exception("Year is not integer.");
        }

        if ($year > date('Y') + 1 || $year < 1992) {
            throw new Exception("Year is out of allowed range.");
        }

        $api = new BlueAlliance($this->TConfig);
        $out = $api->getEvents($year);

        if ($out !== false) {
            $header = array(
                'Event Key', 'Name', 'Type'
            );

            $csv = Export::csv($header, $out);

            return $csv;
        }

        return 'No data.';
    }

    /**
     * Rankings Command
     *
     * Creates a CSV table using the team number and rank for the input event.
     * @param string $key
     * @return string
     * @throws Exception
     */
    private function commandRankings($key) {
        if (!is_string($key)) {
            throw new Exception("Event key is not string.");
        }

        $api = new BlueAlliance($this->TConfig);
        $rankings = $api->getRankings($key);
        $oprs = $api->getOPRs($key);

        $out = array();

        if ($rankings !== false) {
            foreach ($rankings as $item) {
                if ($oprs !== false) {
                    $opr = $oprs[$item['team']];
                } else {
                    $opr = '-';
                }

                $out[] = array(
                    'team' => $item['team'],
                    'rank' => $item['rank'],
                    'opr'  => $opr
                );
            }

            if ($out !== false) {
                $header = array(
                    'Team', 'Rank', 'OPR'
                );

                $csv = Export::csv($header, $out);

                return $csv;
            }
        }

        return 'No data.';
    }
}