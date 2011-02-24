<?php

declare(encoding='UTF-8');
namespace jolt\form;

require_once('jolt/form_controller.php');

abstract class writer extends \jolt\form_controller {

	abstract public function write();

}