<?php

declare(encoding='UTF-8');
namespace Jolt;

require_once 'Jolt/Jolt.php';

class Dispatcher {
	
	private $application_path = NULL;
	
	private $controller_path = NULL;
	
	private $layout_path = NULL;
	
	private $route = NULL;
	
	private $controller = NULL;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		$this->controller = NULL;
		$this->route = NULL;
	}
	

	/**
	 * Dispatches routes based on the controllers and actions they specify.
	 * 
	 * @todo Currently this only works with Jolt_Route_Named objects, needs to work with Jolt_Route_Restful ones.
	 * 
	 */
	public function dispatch() {
		/* Must have a route before dispatching. */
		$route = $this->getRoute();
		if ( true === is_null($route) ) {
			throw new \Jolt\Exception('dispatcher_route_is_null');
		}
		
		
		$controller_file = $route->getControllerFile();
		
		$application_path = $this->getApplicationPath();
		$controller_path = $this->getControllerPath();

		$ds = DIRECTORY_SEPARATOR;
		$controller_path = implode($ds, array($application_path, $controller_path, $controller_file));
		
		$controller = Jolt::buildClass($controller_path, $route->getController());
		if ( false === $controller ) {
			throw new \Jolt\Exception("dispatcher_controller_can_not_be_loaded: {$controller_path}");
		}
		
		$this->setController($controller);
		
		/* Now that we have an active controller, ensure it has the action specified by the route. */
		$action = $route->getAction();
		
		/* The action needs to be a 1:1 to the method in the controller, no appending/prepending words to it. */
		$action_method = new \ReflectionMethod($controller, $action);

		/* Determine if any additional fake parameters need to be added to avoid any warnings. */
		$param_count = $action_method->getNumberOfRequiredParameters();
		$argv = $route->getArgv();
		$argc = count($argv);
		if ( $param_count !== $argc ) {
			if ( $param_count > $argc ) {
				$argv = array_pad($argv, $param_count, NULL);
			}
		}

		/* Invoke the method, generally static methods should not be used. */
		if ( true === $action_method->isPublic() ) {
			if ( true === $action_method->isStatic() ) {
				$success = $action_method->invokeArgs(NULL, $argv);
			} else {
				$success = $action_method->invokeArgs($controller, $argv);
			}
		}
		
		if ( false === $success ) {
			throw new \Jolt\Exception('dispatcher_controller_failed_to_execute');
		}
		
		/**
		 * Controller has it's own rendered view stored within it, and knows if it has a layout.
		 * The Controller has already rendered both of these, so we just need to
		 * collect the rendered view data and return it to the client.
		 * 1. Build a Jolt_Client object.
		 * 2. Pass the data to the Client from the Controller.
		 * 3. The Client takes over and returns the data to the main application file with correct headers.
		 */
		
	}
	
	
	
	public function getApplicationPath() {
		return $this->application_path;
	}
	
	public function getController() {
		return $this->controller;
	}
	
	public function getControllerPath() {
		return $this->controller_path;
	}
	
	public function getLayoutPath() {
		return $this->layout_path;
	}
	
	public function getRoute() {
		return $this->route;
	}
	
	
	
	
	public function setApplicationPath($application_path) {
		$this->application_path = rtrim($application_path, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function setController(Jolt_Controller $controller) {
		$this->controller = clone $controller;
		return $this;
	}
	
	public function setControllerPath($controller_path) {
		$this->controller_path = rtrim($controller_path, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function setLayoutPath($layout_path) {
		$this->layout_path = rtrim($layout_path, DIRECTORY_SEPARATOR);
		return $this;
	}
	
	public function setRoute(Route $route) {
		$this->route = clone $route;
		return $this;
	}
	
	
}