<?php

declare(encoding='UTF-8');
namespace jolt;

class jolt {

	private $client = NULL;
	private $controller_locator = NULL;
	private $dispatcher = NULL;
	private $router = NULL;
	private $settings = NULL;
	private $view = NULL;

	public function __construct() {

	}

	public function attach_client(\jolt\client $client) {
		$this->client = clone $client;
		return $this;
	}

	public function attach_settings(\jolt\vo $settings) {
		$this->settings = clone $settings;
		return $this;
	}

	public function attach_controller_locator(\jolt\controller\locator $locator) {
		$this->controller_locator = clone $locator;
		return $this;
	}

	public function attach_dispatcher(\jolt\dispatcher $dispatcher) {
		$this->dispatcher = clone $dispatcher;
		return $this;
	}

	public function attach_router(\jolt\router $router) {
		$this->router = clone $router;
		return $this;
	}

	public function attach_view(\jolt\view $view) {
		$this->view = clone $view;
		return $this;
	}

	public function execute($request_method=NULL, $path=NULL) {
		$settings = $this->settings;

		$this->router->set_route_parameter($settings->route_parameter);

		$this->view
			->set_css_path($settings->css_path)
			->set_images_path($settings->images_path)
			->set_javascript_path($settings->javascript_path)
			->set_route_parameter($settings->route_parameter)
			->set_secure_url($settings->secure_url)
			->set_url($settings->url)
			->set_use_rewrite($settings->use_rewrite)
			->set_view_path($settings->view_path);

		if (!is_null($request_method)) {
			$this->router->set_request_method($request_method);
		}

		$route = $this->router->execute($path);
		$this->dispatcher
			->attach_locator($this->controller_locator)
			->attach_route($route)
			->attach_settings($settings)
			->attach_view($this->view)
			->execute();

		$this->client->attach_controller($this->dispatcher->get_controller());
		return $this->client;
	}

	public function get_router() {
		return $this->router;
	}

	public function get_view() {
		return $this->view;
	}

}