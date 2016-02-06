<?php
/**
 * File: main.php
 * User: zacharydubois
 * Date: 2016-02-05
 * Time: 19:07
 * Project: Alliance-Scraper
 */

namespace alliance;


define('DS', DIRECTORY_SEPARATOR);
define('PATH', 'phar://Alliance-Scraper.phar' . DS);
define('APP', PATH . 'app' . DS);

require APP . 'controller' . DS . 'Exception.php';
require APP . 'controller' . DS . 'TConfig.php';
require APP . 'controller' . DS . 'HTTP.php';
require APP . 'controller' . DS . 'File.php';
require APP . 'controller' . DS . 'BlueAlliance.php';
require APP . 'controller' . DS . 'Parse.php';
require APP . 'controller' . DS . 'Export.php';
require APP . 'controller' . DS . 'App.php';

try {
    $app = new App();
    $app->run($argv);
} catch (Exception $e) {
    echo $e->getMessage();
}