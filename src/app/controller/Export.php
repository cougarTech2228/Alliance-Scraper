<?php
/**
 * File: Export.php
 * User: zacharydubois
 * Date: 2016-02-05
 * Time: 23:22
 * Project: Alliance-Scraper
 */

namespace alliance;


/**
 * Class Export
 *
 * Export to various formats.
 *
 * @package alliance
 */
class Export {
    /**
     * CSV
     *
     * Creates a CSV using fputcsv().
     * Returns a CSV.
     *
     * @param array $header
     * @param array $data
     * @return string
     * @throws Exception
     */
    public static function csv(array $header, array $data) {
        if (!is_array($data)) {
            throw new Exception("CSV is not array.");
        }

        $out = fopen('php://temp', 'r+');

        fputcsv($out, $header);

        foreach ($data as $row) {
            fputcsv($out, $row);
        }

        rewind($out);

        $csv = '';

        while (!feof($out)) {
            $csv .= fread($out, 8192);
        }

        fclose($out);

        return $csv;
    }
}