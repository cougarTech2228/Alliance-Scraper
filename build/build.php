<?php
/**
 * File: build.php
 * User: zacharydubois
 * Date: 2016-02-06
 * Time: 00:52
 * Project: Alliance-Scraper
 */

define('DS', DIRECTORY_SEPARATOR);
define('BUILD_PATH', __DIR__ . DS);
define('SRC_PATH', BUILD_PATH . '..' . DS . 'src' . DS);

if (!isset($argv[1]) || !isset($argv[2])) {
    throw new Exception("Required parameters not set.");
}

echo "Creating teamid.json..." . PHP_EOL;
if (!file_put_contents(SRC_PATH . 'teamid.json', json_encode(array('id' => $argv[1], 'url' => $argv[2])))) {
    throw new Exception("Failed to write teamid.json");
}

echo "Creating phar..." . PHP_EOL;
$phar = new Phar(BUILD_PATH . 'Alliance-Scraper.phar');
echo "Building..." . PHP_EOL;
$phar->buildFromDirectory(SRC_PATH);
echo "Setting defualt stub..." . PHP_EOL;
$phar->setStub($phar->createDefaultStub('main.php'));

echo "Done." . PHP_EOL;
