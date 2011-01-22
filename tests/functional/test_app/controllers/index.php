<?php

declare(encoding='UTF-8');
namespace jolt_app;

use \jolt\controller;

require_once('jolt/controller.php');

class index extends controller {

	private $sum = 0;

	public function init() {
		$this->sum = mt_rand(1, 100);
	}

	public function get_sum() {
		return $this->sum;
	}

	public function index_action() {
		echo 'Hi, from Jolt!', PHP_EOL;
	}

	public function view_action() {
		$this->render('welcome');
	}

	public function param_action($id, $name) {
		echo count(func_get_args());
	}

	public static function static_action() {
		echo 'static content';
	}
}