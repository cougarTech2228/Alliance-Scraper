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
define('PATH', __DIR__ . DS);
define('APP', PATH . 'app' . DS);

require APP . 'controller' . DS . 'Exception.php';
require APP . 'controller' . DS . 'TConifg.php';
require APP . 'controller' . DS . 'HTTP.php';
require APP . 'controller' . DS . 'File.php';
require APP . 'controller' . DS . 'BlueAlliance.php';
require APP . 'controller' . DS . 'Parse.php';
require APP . 'controller' . DS . 'Export.php';