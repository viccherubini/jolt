<?php

declare(encoding='UTF-8');
namespace JoltTest;

require_once 'PHPUnit/Framework.php';
require_once 'TestCase.php';

$includePath = get_include_path();
$joltTestPath = dirname(__FILE__);
$joltPath = realpath($joltTestPath . '/../');

set_include_path(implode(PATH_SEPARATOR, array($includePath, $joltPath, $joltTestPath)));

define('DS', DIRECTORY_SEPARATOR, false);
define('DIRECTORY_TESTS', $joltTestPath, false);
define('DIRECTORY_DB', $joltTestPath . DS . 'db', false);
define('DIRECTORY_APP', DIRECTORY_TESTS . DS . 'app', false);
define('DIRECTORY_CONTROLLERS', DIRECTORY_APP . DS . 'controllers', false);
define('DIRECTORY_VIEWS', DIRECTORY_APP . DS . 'views', false);

require_once 'Jolt/Exception.php';
require_once 'Jolt/Route.php';

// Delete all of the coverage docs
shell_exec('rm -rf coverage');