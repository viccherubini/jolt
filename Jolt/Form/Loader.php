<?php

declare(encoding='UTF-8');
namespace Jolt\Form;
use \Jolt\FormController;

require_once 'Jolt/FormController.php';

abstract class Loader extends FormController {

	abstract public function load();

}
