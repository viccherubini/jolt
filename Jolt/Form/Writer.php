<?php

declare(encoding='UTF-8');
namespace Jolt\Form;
use \Jolt\Form\Controller;

require_once 'Jolt/Form/Controller.php';

abstract class Writer extends Controller {

	abstract public function write();

}