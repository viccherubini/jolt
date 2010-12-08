<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Exception.php';

define('JOLT_VERSION', '0.0.6', false);

/**
 * This class is an entrance for building an application. It is entirely
 * static and gives you access to building new objects, starting the
 * application, and basic CSRF protection.
 *
 * @author vmc <vmc@leftnode.com>
 */
class Jolt {

	private $client = NULL;
	private $controllerLocator = NULL;
	private $dispatcher = NULL;
	private $router = NULL;
	private $settings = NULL;
	private $view = NULL;

	public function __construct() {

	}

	public function attachClient(\Jolt\Client $client) {
		$this->client = clone $client;
		return $this;
	}

	public function attachSettings(\Jolt\Settings $settings) {
		$this->settings = clone $settings;
		return $this;
	}

	public function attachControllerLocator(\Jolt\Controller\Locator $locator) {
		$this->controllerLocator = clone $locator;
		return $this;
	}

	public function attachDispatcher(\Jolt\Dispatcher $dispatcher) {
		$this->dispatcher = clone $dispatcher;
		return $this;
	}

	public function attachRouter(\Jolt\Router $router) {
		$this->router = clone $router;
		return $this;
	}

	public function attachView(\Jolt\View $view) {
		$this->view = clone $view;
		return $this;
	}

	public function execute() {
		$settings = $this->settings;

		$this->router
			->setRouteParameter($settings->route_parameter);

		$this->view
			->setCssPath($settings->css_path)
			->setImagePath($settings->image_path)
			->setJsPath($settings->js_path)
			->setRouteParameter($settings->route_parameter)
			->setSecureUrl($settings->secure_url)
			->setUrl($settings->url)
			->setUseRewrite($settings->use_rewrite)
			->setViewPath($settings->view_path);

		$route = $this->router
			->execute();

		$this->dispatcher
			->setControllerPath($settings->controller_path)
			->attachLocator($this->controllerLocator)
			->attachRoute($route)
			->attachView($this->view)
			->execute();

		$this->client
			->attachController($this->dispatcher->getController());

		return $this->client;
	}

	public function getRouter() {
		return $this->router;
	}

	public function getView() {
		return $this->view;
	}

}