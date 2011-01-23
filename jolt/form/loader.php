<?php

declare(encoding='UTF-8');
namespace jolt\form;
use \jolt\form_controller;

require_once('jolt/form_controller.php');

abstract class loader extends form_controller {

	abstract public function load();

}
