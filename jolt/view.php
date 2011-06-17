<?php

declare(encoding='UTF-8');
namespace jolt;

class view {

	private $css_path = NULL;
	private $javascript_path = NULL;
	private $images_path = NULL;
	private $secure_url = NULL;
	private $url = NULL;
	private $use_rewrite = false;
	private $view_path = NULL;

	private $rendered_view = NULL;

	private $variables = array();
	private $javascripts = array();
	private $css = array();
	private $blocks = array();

	const ext = '.phtml';

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

	public function incl($view, $return=false) {
		$rendered_view = $this->render($view)
			->get_rendered_view();
		if (!$return) {
			echo $rendered_view;
			return true;
		} else {
			return $rendered_view;
		}
	}

	public function register($variable, $value) {
		$this->__set($variable, $value);
		return $this;
	}

	public function render($view) {
		$view = $this->append_extension($view, self::ext);

		// Find the view file
		$view_file = $this->view_path . $view;
		if (!is_file($view_file)) {
			throw new \jolt\exception("View file '".$view_file."' not found.");
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
		if ($local_file) {
			$css_file = $this->append_extension($css_file, '.css');
			$root_url = $this->get_root_url();
			$css_file = $root_url.$this->css_path.$css_file;
		}

		$link_tag = sprintf('<link type="text/css" rel="stylesheet" href="%s" media="%s">%s', $css_file, $media, PHP_EOL);
		return $link_tag;
	}

	public function dropdown($name, $values, $texts, $default=NULL, $attributes=NULL, $sanitize=true) {
		$options = NULL;
		$i = 0;

		if (!is_array($values) || !is_array($texts) ) {
			return NULL;
		}

		$values_length = count($values);
		$texts_length = count($texts);
		if (($values_length < 1 || $texts_length < 1) || ($values_length !== $texts_length)) {
			return NULL;
		}

		foreach ($texts as $display) {
			$value = $values[$i];
			$selected = ($default === $value ? 'selected' : NULL);

			if ($sanitize) {
				$value = $this->safe($value);
				$display = $this->safe($display);
			}

			$options .= sprintf('<option value="%s" %s>%s</option>', $value, $selected, $display);
			$i++;
		}

		$select = sprintf('<select name="%s" %s>%s</select>', $name, $attributes, $options);
		return $select;
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
			$root_url = $this->get_root_url();
			$img_src = $root_url.$this->images_path.$img_src;
		}

		$img_tag = sprintf('<img src="%s" alt="%s" title="%s" %s>%s', $img_src, $alt_text, $alt_text, $tag_attributes, PHP_EOL);
		return $img_tag;
	}

	public function javascript($javascript_file, $local_file=true) {
		$javascript_file = $this->append_extension($javascript_file, '.js');

		if ($local_file) {
			$root_url = $this->get_root_url();
			$javascript_file = $root_url.$this->javascript_path.$javascript_file;
		}

		$script_tag = sprintf('<script src="%s" type="text/javascript"></script>%s', $javascript_file, PHP_EOL);
		return $script_tag;
	}

	public function js($javascript_file, $local_file=true) {
		return $this->javascript($javascript_file, $local_file);
	}

	public function include_css() {
		$css = $this->get_css();

		$included_css = NULL;
		foreach ($css as $css_tag) {
			$included_css .= $css_tag;
		}
		return $included_css;
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

	public function register_css($css_file, $media='screen', $local_file=true) {
		$this->css[] = $this->css($css_file, $media, $local_file);
		return $this;
	}

	public function register_javascript($javascript_file, $javascript_class=NULL) {
		$javascript = array();
		$javascript['script'] = $this->javascript($javascript_file);
		if (!empty($javascript_class)) {
			$javascript['init'] = '(new '.$javascript_class.'().init());';
		}

		$this->javascripts[] = $javascript;
		return $this;
	}

	public function url() {
		$argc = func_num_args();
		$argv = func_get_args();

		$http_parameters = NULL;
		$url_prefix = $this->get_url();
		if ($argc > 0 && is_bool($argv[$argc-1])) {
			$argc--;
			$secure = array_pop($argv);
			if ($secure) {
				$url_prefix = $this->get_secure_url();
			}

			if (is_array($argv[$argc-1])) {
				$argc--;
				$http_parameters = array_pop($argv);
				$http_parameters = http_build_query($http_parameters);
			}
		}

		$p = $this->make_url_parameters($argc, $argv);
		$url = $url_prefix.$p;
		if (!empty($http_parameters)) {
			$url .= '?'.$http_parameters;
		}

		return $url;
	}

	public function set_css_path($css_path) {
		$this->css_path = $this->append_directory_separator($css_path);
		return $this;
	}

	public function set_images_path($images_path) {
		$this->images_path = $this->append_directory_separator($images_path);
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
		$this->secure_url = $this->append_url_slash(trim($secure_url));
		return $this;
	}

	public function set_url($url) {
		$this->url = $this->append_url_slash(trim($url));
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

	public function get_images_path() {
		return $this->images_path;
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

	public function get_css() {
		return $this->css;
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

	private function append_url_slash($url) {
		$url_last_char_index = strlen($url)-1;
		if ($url[$url_last_char_index] != '/') {
			$url .= '/';
		}
		return $url;
	}

	private function get_root_url() {
		if ($this->is_secure()) {
			return $this->get_secure_url();
		}
		return $this->get_url();
	}

	private function is_secure() {
		// Unfortunately only way to detect if a page is secure or
		// not is to use the $_SERVER superglobal
		$is_secure = false;
		if (isset($_SERVER)) {
			if (array_key_exists('HTTPS', $_SERVER)) {
				$is_secure = ('on' === strtolower($_SERVER['HTTPS']));
			}
		}
		return $is_secure;
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
			$route = '/'.implode('/', array_slice($argv, 1));
		}

		$parameters = $root.$route;
		if (!$this->use_rewrite) {
			$route_parameter = $this->get_route_parameter();
			$parameters = 'index.php?'.$route_parameter.'='.$parameters;
		}
		return $parameters;
	}

}