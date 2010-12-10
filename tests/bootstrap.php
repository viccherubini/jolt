<?php

declare(encoding='UTF-8');
namespace JoltTest;

require_once 'PHPUnit/Autoload.php';
require_once 'testcase.php';

$includePath = get_include_path();
$joltTestPath = dirname(__FILE__);
$joltPath = realpath($joltTestPath . '/../');

set_include_path(implode(PATH_SEPARATOR, array($includePath, $joltPath, $joltTestPath)));

define('DS', DIRECTORY_SEPARATOR, false);
define('DIRECTORY_TESTS', $joltTestPath, false);
define('DIRECTORY_APP', DIRECTORY_TESTS . DS . 'test_app', false);
define('DIRECTORY_DB', DIRECTORY_APP . DS . 'db', false);
define('DIRECTORY_CONTROLLERS', DIRECTORY_APP . DS . 'controllers', false);
define('DIRECTORY_VIEWS', DIRECTORY_APP . DS . 'views', false);

// Rather than having each test include the files it needs, just include
// them all at once.
require_once 'jolt/framework.php';

// Delete all of the coverage docs
shell_exec('rm -rf coverage');
