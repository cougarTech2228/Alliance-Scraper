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
        $command = $args[1];
        $param = $args[2];
        $file = $args[3];

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

        if ($file !== null) {
            echo "Saving to " . $file . PHP_EOL;

            if (!File::write($file, $out)) {
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
        $out = $api->getRankings($key);

        if ($out !== false) {
            $header = array(
                'Rank', 'Team'
            );

            $csv = Export::csv($header, $out);

            return $csv;
        }

        return 'No data.';

    }
}