<?php

declare(encoding='UTF-8');
namespace jolt;

class view {

	private $css_path = NULL;
	private $javascript_path = NULL;
	private $image_path = NULL;
	private $secure_url = NULL;
	private $url = NULL;
	private $use_rewrite = false;
	private $view_path = NULL;

	private $rendered_view = NULL;

	private $variables = array();
	private $javascripts = array();
	private $blocks = array();

	const EXT = '.phtml';

	public function __construct() {

	}

	public function __destruct() {

	}

	public function __set($k, $v) {
		$this->variables[$k] = $v;
		return true;
	}

	public function __get($k) {
		if (array_key_exists($k, $this->variables)) {
			return $this->variables[$k];
		}
		return NULL;
	}

	public function add_block($block_name, $block) {
		$this->blocks[$block_name] = $block;
		return $this;
	}

	public function render($view) {
		$view = $this->append_extension($view, self::EXT);

		// Find the view file
		$view_file = $this->view_path . $view;
		if (!is_file($view_file)) {
			throw new \Jolt\Exception("View file '".$view_file."' not found.");
		}

		extract($this->variables);
		ob_start();
			require($view_file);
		$this->rendered_view = ob_get_clean();

		return $this;
	}

	public function safe($v) {
		return htmlentities($v, ENT_COMPAT, 'UTF-8');
	}

	public function css($css_file, $media='screen', $local_file=true) {
		$css_file = $this->append_extension($css_file, '.css');

		if ($local_file) {
			$css_file = '/' . $this->css_path . $css_file;
		}

		$link_tag = sprintf('<link type="text/css" rel="stylesheet" href="%s" media="%s">%s', $css_file, $media, PHP_EOL);
		return $link_tag;
	}

	public function href($url, $text, $tag_attributes=NULL, $local_url=true, $secure=false) {
		if ($local_url) {
			$url = $this->url($url, $secure);
		}

		$text = $this->safe($text);
		$href = sprintf('<a href="%s" %s>%s</a>%s', $url, $tag_attributes, $text, PHP_EOL);
		return $href;
	}

	public function img($img_src, $alt_text=NULL, $tag_attributes=NULL, $local_file=true) {
		if ($local_file) {
			$img_src = '/' . $this->image_path . $img_src;
		}

		$img_tag = sprintf('<img src="%s" alt="%s" title="%s" %s>%s', $img_src, $alt_text, $alt_text, $tag_attributes, PHP_EOL);
		return $img_tag;
	}

	public function javascript($javascript_file, $local_file=true) {
		$javascript_file = $this->append_extension($javascript_file, '.js');

		if ($local_file) {
			$javascript_file = '/' . $this->javascript_path . $javascript_file;
		}

		$script_tag = sprintf('<script src="%s" type="text/javascript"></script>%s', $javascript_file, PHP_EOL);
		return $script_tag;
	}

	public function include_javascript($type='script') {
		$javascripts = $this->get_javascripts();

		$included_javascript = NULL;
		foreach ($javascripts as $script) {
			if (array_key_exists($type, $script)) {
				$included_javascript .= $script[$type];
			}
		}
		return $included_javascript;
	}

	public function register_javascript($javascript, $javascript_class=NULL) {
		$javascript = array();
		$javascript['script'] = $this->javascript($jsScript);
		if (!empty($javascript_class)) {
			$javascript['ready'] = '(new ' . $javascript_class . '().ready());';
		}

		$this->javascripts[] = $javascript;
		return $this;
	}

	public function url() {
		$argc = func_num_args();
		$argv = func_get_args();

		$url_prefix = $this->url;
		if ($argc > 0 && is_bool($argv[$argc-1])) {
			$secure = array_pop($argv);
			$argc = count($argv);
			if ($secure) {
				$url_prefix = $this->secure_url;
			}
		}

		$p = $this->make_url_parameters($argc, $argv);
		$url = $url_prefix . $p;
		return $url;
	}

	public function set_css_path($css_path) {
		$this->css_path = $this->append_directory_separator($css_path);
		return $this;
	}

	public function set_image_path($image_path) {
		$this->image_path = $this->append_directory_separator($image_path);
		return $this;
	}

	public function set_javascript_path($javascript_path) {
		$this->javascript_path = $this->append_directory_separator($javascript_path);
		return $this;
	}

	public function set_route_parameter($route_parameter) {
		$this->route_parameter = trim($route_parameter);
		return $this;
	}

	public function set_secure_url($secure_url) {
		$this->secure_url = trim($secure_url);
		return $this;
	}

	public function set_url($url) {
		$this->url = trim($url);
		return $this;
	}

	public function set_use_rewrite($use_rewrite) {
		if (!is_bool($use_rewrite)) {
			$use_rewrite = false;
		}

		$this->use_rewrite = $use_rewrite;
		return $this;
	}

	public function set_view_path($view_path) {
		$this->view_path = $this->append_directory_separator($view_path);
		return $this;
	}

	public function get_blocks() {
		return $this->blocks;
	}

	public function get_block($block_name) {
		if (array_key_exists($block_name, $this->blocks)) {
			return $this->blocks[$block_name];
		}
		return NULL;
	}

	public function get_css_path() {
		return $this->css_path;
	}

	public function get_image_path() {
		return $this->image_path;
	}

	public function get_javascript_path() {
		return $this->javascript_path;
	}

	public function get_rendered_view() {
		return $this->rendered_view;
	}

	public function get_route_parameter() {
		return $this->route_parameter;
	}

	public function get_secure_url() {
		return $this->secure_url;
	}

	public function get_url() {
		return $this->url;
	}

	public function get_use_rewrite() {
		return $this->use_rewrite;
	}

	public function get_view_path() {
		return $this->view_path;
	}

	public function get_variables() {
		return $this->variables;
	}

	public function get_javascripts() {
		return $this->javascripts;
	}

	private function append_extension($file, $ext) {
		if (0 == preg_match('/\\'.$ext.'$/i', $file)) {
			$file .= $ext;
		}
		return $file;
	}

	private function append_directory_separator($path) {
		$path_length = strlen(trim($path)) - 1;
		if ($path_length >= 0 && $path[$path_length] != DIRECTORY_SEPARATOR) {
			$path .= DIRECTORY_SEPARATOR;
		}
		return $path;
	}

	private function make_url_parameters($argc, $argv) {
		if (0 == $argc) {
			return NULL;
		}

		$route = NULL;
		$root = '';
		if ('/' != $argv[0]) {
			$root = $argv[0];
		}

		if ($argc > 1) {
			$route = '/' . implode('/', array_slice($argv, 1));
		}

		$parameters = $root . $route;
		if (!$this->use_rewrite) {
			$route_parameter = $this->get_route_parameter();
			$parameters = 'index.php?'.$route_parameter.'='.$parameters;
		}
		return $parameters;
	}

}