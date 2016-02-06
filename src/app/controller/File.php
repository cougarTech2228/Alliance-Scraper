<?php
/**
 * File: File.php
 * User: zacharydubois
 * Date: 2016-02-05
 * Time: 19:13
 * Project: Alliance-Scraper
 */

namespace alliance;

/**
 * Class File
 *
 * Class used to interact with files.
 *
 * @package alliance
 */
class File {
    /**
     * Read
     *
     * Validates that file is a string.
     * Returns false if file cannot be read or file does not exist.
     *
     * @param string $file
     * @return false|string
     * @throws Exception
     */
    public static function read($file) {
        if (!is_string($file)) {
            throw new Exception("Input file is not string: " . $file);
        }

        if (!file_exists($file) || is_readable($file)) {
            return false;
        }

        return file_get_contents($file);
    }

    /**
     * Write
     *
     * Validates that the input can be written to and is string.
     * Returns false if the file cannot be written (permission error).
     * Returns true when file has successfully been written.
     *
     * @param string $file
     * @param string $data
     * @return bool
     * @throws Exception
     */
    public static function write($file, $data) {
        if (!is_string($file) || !is_string($data)) {
            throw new Exception("Input file or data is not string: " . $file . " Data:" . $data);
        }

        if (!is_writable($file)) {
            return false;
        }

        if (!file_put_contents($file, $data)) {
            throw new Exception("Could not put contents in file: " . $file . " Data: " . $data);
        }

        return true;
    }
}