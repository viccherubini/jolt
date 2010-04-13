<?php

$jolt_test_path = dirname(__FILE__);
$jolt_path = $jolt_test_path . '/../';
set_include_path(get_include_path() . PATH_SEPARATOR . $jolt_path . PATH_SEPARATOR . $jolt_test_path);

require_once 'PHPUnit/Framework.php';
require_once 'JoltCore/TestCase.php';