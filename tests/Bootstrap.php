<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'PHPUnit/Framework.php';
require_once 'TestCase.php';

$jolt_test_path = dirname(__FILE__);
$jolt_path = $jolt_test_path . '/../';
set_include_path(get_include_path() . PATH_SEPARATOR . $jolt_path . PATH_SEPARATOR . $jolt_test_path);

define('TEST_DIRECTORY', $jolt_test_path, false);
define('APPLICATION_DIRECTORY', 'application', false);