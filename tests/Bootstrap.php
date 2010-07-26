<?php

declare(encoding='UTF-8');

require_once 'PHPUnit/Framework.php';
require_once 'TestCase.php';

$joltTestPath = dirname(__FILE__);
$joltPath = $joltTestPath . '/../';
set_include_path(get_include_path() . PATH_SEPARATOR . $joltPath . PATH_SEPARATOR . $joltTestPath);

define('TEST_DIRECTORY', $joltTestPath, false);
define('APPLICATION_DIRECTORY', 'application', false);

require_once 'Jolt/Exception.php';