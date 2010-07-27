<?php

declare(encoding='UTF-8');
namespace JoltTest;

require_once 'PHPUnit/Framework.php';
require_once 'TestCase.php';

$includePath = get_include_path();
$joltTestPath = dirname(__FILE__);
$joltPath = realpath($joltTestPath . '/../');

set_include_path(implode(PATH_SEPARATOR, array($includePath, $joltPath, $joltTestPath)));

//define('TEST_DIRECTORY', $joltTestPath, false);
//define('APPLICATION_DIRECTORY', 'application', false);

require_once 'Jolt/Exception.php';