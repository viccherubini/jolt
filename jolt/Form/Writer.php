<?php

declare(encoding='UTF-8');
namespace Jolt\Form;
use \Jolt\FormController;

require_once 'Jolt/FormController.php';

abstract class Writer extends FormController {

	abstract public function write();

}