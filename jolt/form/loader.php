<?php

declare(encoding='UTF-8');
namespace jolt\form;

require_once('jolt/form_controller.php');

abstract class loader extends \jolt\form_controller {

	abstract public function load();

}