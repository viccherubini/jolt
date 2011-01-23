<?php

declare(encoding='UTF-8');
namespace jolt\form;
use \jolt\FormController;

require_once 'jolt/form_controller.php';

abstract class Loader extends FormController {

	abstract public function load();

}
