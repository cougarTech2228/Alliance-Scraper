<?php
/**
 * File: TConfig.php
 * User: zacharydubois
 * Date: 2016-02-05
 * Time: 20:26
 * Project: Alliance-Scraper
 */

namespace alliance;


/**
 * Class TConfig
 *
 * Team configuration handler.
 * Pulls information from PATH . 'teamid.json'
 *
 * @package alliance
 */
class TConfig {
    private
        $config,
        $path;

    /**
     * TConfig constructor.
     *
     * Initializes $this->config with $this->reload().
     *
     * @throws Exception
     */
    public function __construct() {
        $this->path = PATH . 'teamid.json';
        $this->reload();
    }

    /**
     * Reload from Disk
     *
     * Reloads the team configuration from the disk.
     *
     * @return true
     * @throws Exception
     */
    private function reload() {
        $this->config = json_decode(File::read($this->path), true);

        if (!is_array($this->config)) {
            throw new Exception("Decoded team configuration is not an array.");
        }

        return true;
    }

    /**
     * Read Value from Key
     *
     * Reads input key's value in the team configuration file.
     *
     * @param string $key
     * @return string
     * @throws Exception
     */
    public function read($key) {
        if (!is_string($key)) {
            throw new Exception("Input config key is not string: " . $key);
        }

        // TODO: Validate key exists.
        if ($this->config[$key] === null) {
            throw new Exception("Config key is not set: " . $key);
        }

        return $this->config[$key];
    }

    /**
     * Write TConfig to Disk
     *
     * Encodes and writes team configuration to the disk.
     *
     * @param string $key
     * @param string $value
     * @return true
     * @throws Exception
     */
    public function write($key, $value) {
        if (!is_string($key) || !is_string($value)) {
            throw new Exception("Config key or value for write are not strings: " . $key . " Value: " . $value);
        }

        $this->config[$key] = $value;

        if (!File::write($this->path, json_encode($this->config, JSON_PRETTY_PRINT))) {
            throw new Exception("Failed to write new config to file.");
        }

        $this->reload();

        return true;
    }
}