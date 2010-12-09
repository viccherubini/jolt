<?php

declare(encoding='UTF-8');
namespace JoltTest;

use \Jolt\View,
	\JoltTest\TestCase;

class ViewTest extends TestCase {

	public function test__Set_WritesToVariables() {
		$expected = array('name' => 'vic');

		$view = new View;
		$view->name = 'vic';

		$this->assertEquals($expected, $view->getVariables());
	}

	public function test__Get_RetrievesFromVariables() {
		$name = 'vic';

		$view = new View;
		$view->name = $name;

		$this->assertEquals($name, $view->name);
	}

	public function test__Get_ReturnsNullWhenVariableNotFound() {
		$view = new View;

		$this->assertTrue(is_null($view->name));
	}

	public function testAddBlock_InsertsHtml() {
		$block = '<script type="text/javascript" src="jquery.js"></script>';

		$view = new View;
		$view->addBlock('scripts', $block);

		$this->assertEquals($block, $view->getBlock('scripts'));
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRender_ConfigurationMustBeSet() {
		$view = new View;

		$view->render('view');
	}

	/**
	 * @expectedException \Jolt\Exception
	 */
	public function testRender_ViewFileMustExist() {
		$view = new View;

		$view->render('missing-view');
	}

	/**
	 * @dataProvider providerViewWithVariables
	 */
	public function testRender_ViewRenders($viewName, $variables) {
		$view = new View;
		$view->setViewPath(DIRECTORY_VIEWS);

		foreach ( $variables as $k => $v ) {
			$view->$k = $v;
		}

		$view->render($viewName);

		$this->assertEquals($this->loadRenderedView($viewName), $view->getRenderedView());
	}

	/**
	 * @dataProvider providerUnsafeString
	 */
	public function testSafe_ConvertsEntities($string, $expected) {
		$view = new View;

		$actual = $view->safe($string);

		$this->assertEquals($expected, $actual);
	}

	public function testCss_AppendsExtension() {
		$view = new View;

		$linkTag = $view->css('style');
		$this->assertRegExp('/style.css/', $linkTag);

		$linkTag = $view->css('http://google.com/style', 'screen', false);
		$this->assertRegExp('/style.css/', $linkTag);
	}

	public function testCss_ExternalFile() {
		$view = new View;

		$cssUrl = 'http://google.com/style.css';
		$media = 'screen';

		$matcher = array(
			'tag' => 'link',
			'attributes' => array(
				'type' => 'text/css',
				'rel' => 'stylesheet',
				'href' => $cssUrl,
				'media' => $media
			)
		);

		$linkTag = $view->css($cssUrl, $media, false);
		$this->assertTag($matcher, $linkTag);
	}

	public function testCss_PrintStylesheet() {
		$view = new View;

		$cssUrl = 'http://google.com/style.css';
		$media = 'print';

		$matcher = array(
			'tag' => 'link',
			'attributes' => array(
				'type' => 'text/css',
				'rel' => 'stylesheet',
				'href' => $cssUrl,
				'media' => $media
			)
		);

		$linkTag = $view->css($cssUrl, $media, false);
		$this->assertTag($matcher, $linkTag);
	}

	public function testHref_IsSecure() {
		$secureUrl = 'https://leftnode.com/';

		$view = new View;

		$view->setUseRewrite(true);
		$view->setSecureUrl($secureUrl);

		$matcher = array(
			'tag' => 'a',
			'attributes' => array(
				'href' => "{$secureUrl}path/to/route"
			),
			'content' => 'Click Here'
		);

		$href = $view->href('path/to/route', 'Click Here', NULL, true, true);

		$this->assertTag($matcher, $href);
	}

	public function testHref_HasAttributes() {
		$url = 'http://leftnode.com/';

		$view = new View;
		$view->setUseRewrite(true);
		$view->setUrl($url);

		$matcher = array(
			'tag' => 'a',
			'attributes' => array(
				'href' => "{$url}path/to/route",
				'class' => 'blue-link',
				'id' => 'cancel-account',
				'style' => 'text-decoration: underline'
			),
			'content' => 'Click Here'
		);

		$href = $view->href('path/to/route', 'Click Here', 'class="blue-link" id="cancel-account" style="text-decoration: underline"');

		$this->assertTag($matcher, $href);
	}

	public function testHref_TextIsSafe() {
		$url = 'http://leftnode.com/';

		$view = new View;
		$view->setUseRewrite(true);
		$view->setUrl($url);

		// Not using assertTag() here because it converts
		// HTML entities in the content field back
		$href = $view->href('path/to/route', '<strong>Click Here</strong>', 'class="blue-link"');

		$this->assertRegExp('/&lt;strong&gt;Click Here&lt;\/strong&gt;/', $href);
	}

	public function testImg_LocalFilesStartFromHttpRoot() {
		$imagePath = 'public/images';
		$image = 'image.jpg';

		$view = new View;
		$view->setImagePath($imagePath);

		$matcher = array(
			'tag' => 'img',
			'attributes' => array(
				'src' => "/public/images/{$image}"
			)
		);

		$img = $view->img($image);
		$this->assertTag($matcher, $img);
	}

	public function testImg_ExternalImage() {
		$image = 'http://leftnode.com/public/image/leftnode-logo-box.png';
		$alt = 'Leftnode Logo';
		$title = $alt;

		$view = new View;

		$matcher = array(
			'tag' => 'img',
			'attributes' => array(
				'src' => $image,
				'alt' => $alt,
				'title' => $title
			)
		);

		$img = $view->img($image, $alt, NULL, false);
		$this->assertTag($matcher, $img);
	}

	public function testImg_SupportAttributes() {
		$image = 'http://leftnode.com/public/image/leftnode-logo-box.png';
		$alt = 'Leftnode Logo';
		$title = $alt;

		$view = new View;

		$matcher = array(
			'tag' => 'img',
			'attributes' => array(
				'src' => $image,
				'alt' => $alt,
				'title' => $title,
				'class' => 'logo',
				'id' => 'click-here',
				'style' => 'border: 1px solid black'
			)
		);

		$img = $view->img($image, $alt, 'class="logo" id="click-here" style="border: 1px solid black"', false);
		$this->assertTag($matcher, $img);
	}

	public function testJs_AppendsExtension() {
		$jsPath = 'public/js';
		$jsFile = 'jquery';

		$view = new View;
		$view->setJsPath($jsPath);

		$matcher = array(
			'tag' => 'script',
			'attributes' => array(
				'type' => 'text/javascript',
				'src' => "/{$jsPath}/{$jsFile}.js"
			)
		);

		$scriptTag = $view->js($jsFile);
		$this->assertTag($matcher, $scriptTag);
	}

	public function testJs_ExternalJavascript() {
		$jsFile = 'http://code.jquery.com/jquery-1.4.2.min.js';

		$view = new View;

		$matcher = array(
			'tag' => 'script',
			'attributes' => array(
				'type' => 'text/javascript',
				'src' => $jsFile
			)
		);

		$scriptTag = $view->js($jsFile, false);
		$this->assertTag($matcher, $scriptTag);
	}

	public function testRegisterJs_BuildsArrayOfJsFiles() {
		$view = new View;

		$view->registerJs('jquery');
		$view->registerJs('prototype');

		$javascripts = $view->getJavascripts();

		$this->assertEquals($view->js('jquery'), $javascripts[0]['script']);
		$this->assertEquals($view->js('prototype'), $javascripts[1]['script']);
	}

	public function testRegisterJs_BuildsArrayOfJsObjects() {
		$view = new View;

		$view->registerJs('jquery', 'jQuery');
		$view->registerJs('prototype', 'ProtoType');

		$javascripts = $view->getJavascripts();

		$this->assertEquals('(new jQuery().ready());', $javascripts[0]['ready']);
		$this->assertEquals('(new ProtoType().ready());', $javascripts[1]['ready']);
	}

	public function testUrl_TurnsArgumentsToUrl() {
		$view = new View;

		$expectedUrl = 'path/to/route';
		$actualUrl = $view->url('path', 'to', 'route');

		$this->assertRegExp("#{$expectedUrl}$#", $actualUrl);
	}

	public function testUrl_LastArgumentMakesItSecure() {
		define('SECURE_URL', true);
		define('INSECURE_URL', false);

		$secureUrl = 'https://leftnode.com/';
		$url = 'http://leftnode.com/';

		$view = new View;
		$view->setUseRewrite(true);
		$view->setUrl($url);
		$view->setSecureUrl($secureUrl);

		$expectedSecureUrl = "{$secureUrl}path/to/route";
		$actualSecureUrl = $view->url('path', 'to', 'route', true);
		$this->assertEquals($expectedSecureUrl, $actualSecureUrl);

		$expectedUrl = "{$url}path/to/route";
		$actualUrl = $view->url('path', 'to', 'route', false);
		$this->assertEquals($expectedUrl, $actualUrl);

		$expectedUrl = "{$url}path/to/route";
		$actualUrl = $view->url('path', 'to', 'route');
		$this->assertEquals($expectedUrl, $actualUrl);

		$expectedUrl = "{$url}path/to/route/1";
		$actualUrl = $view->url('path', 'to', 'route', 1);
		$this->assertEquals($expectedUrl, $actualUrl);

		$expectedSecureUrl = "{$secureUrl}path/to/route";
		$actualSecureUrl = $view->url('path', 'to', 'route', SECURE_URL);
		$this->assertEquals($expectedSecureUrl, $actualSecureUrl);

		$expectedUrl = "{$url}path/to/route";
		$actualUrl = $view->url('path', 'to', 'route', INSECURE_URL);
		$this->assertEquals($expectedUrl, $actualUrl);
	}

	public function testUrl_DefaultUrl() {
		$secureUrl = 'https://leftnode.com/';
		$url = 'http://leftnode.com/';

		$view = new View;
		$view->setUseRewrite(true);
		$view->setUrl($url);
		$view->setSecureUrl($secureUrl);

		$this->assertEquals($url, $view->url());
		$this->assertEquals($url, $view->url(false));
		$this->assertEquals($secureUrl, $view->url(true));
	}

	public function testSetCssPath_AppendsEndingDirectorySeparator() {
		$cssPathWithoutDirectorySeparator = 'public/css';
		$cssPathWithDirectorySeparator = 'public/css' . DIRECTORY_SEPARATOR;

		$view = new View;

		$view->setCssPath($cssPathWithoutDirectorySeparator);
		$this->assertEquals($cssPathWithDirectorySeparator, $view->getCssPath());

		$view->setCssPath($cssPathWithDirectorySeparator);
		$this->assertEquals($cssPathWithDirectorySeparator, $view->getCssPath());
	}

	public function testSetImagePath_AppendsEndingDirectorySeparator() {
		$imagePathWithoutDirectorySeparator = 'public/images';
		$imagePathWithDirectorySeparator = 'public/images' . DIRECTORY_SEPARATOR;

		$view = new View;

		$view->setImagePath($imagePathWithoutDirectorySeparator);
		$this->assertEquals($imagePathWithDirectorySeparator, $view->getImagePath());

		$view->setImagePath($imagePathWithDirectorySeparator);
		$this->assertEquals($imagePathWithDirectorySeparator, $view->getImagePath());
	}

	public function testSetJsPath_AppendsEndingDirectorySeparator() {
		$jsPathWithoutDirectorySeparator = 'public/js';
		$jsPathWithDirectorySeparator = 'public/js' . DIRECTORY_SEPARATOR;

		$view = new View;

		$view->setJsPath($jsPathWithoutDirectorySeparator);
		$this->assertEquals($jsPathWithDirectorySeparator, $view->getJsPath());

		$view->setJsPath($jsPathWithDirectorySeparator);
		$this->assertEquals($jsPathWithDirectorySeparator, $view->getJsPath());
	}

	public function testSetRouteParameter_IsTrimmed() {
		$routeParameter = ' __u   	';
		$routeParameterTrimmed = '__u';

		$view = new View;
		$view->setRouteParameter($routeParameter);

		$this->assertEquals($routeParameterTrimmed, $view->getRouteParameter());
	}

	public function testSetSecureUrl_IsTrimmed() {
		$secureUrl = " https://joltcore.org/ \t   ";
		$secureUrlTrimmed = "https://joltcore.org/";

		$view = new View;
		$view->setSecureUrl($secureUrl);

		$this->assertEquals($secureUrlTrimmed, $view->getSecureUrl());
	}

	public function testSetUrl_IsTrimmed() {
		$url = " http://joltcore.org/ \t   ";
		$urlTrimmed = "http://joltcore.org/";

		$view = new View;
		$view->setUrl($url);

		$this->assertEquals($urlTrimmed, $view->getUrl());
	}

	public function testSetUseRewrite_IsFalse() {
		$useRewriteString = 'string';

		$view = new View;
		$view->setUseRewrite($useRewriteString);

		$this->assertFalse($view->getUseRewrite());
	}

	public function testSetUseRewrite_IsBoolean() {
		$view = new View;

		$view->setUseRewrite(true);
		$this->assertTrue($view->getUseRewrite());

		$view->setUseRewrite(false);
		$this->assertFalse($view->getUseRewrite());
	}

	public function testSetViewPath_AppendsSeparator() {
		$view = new View;
		$view->setViewPath(DIRECTORY_VIEWS);

		$this->assertEquals(DIRECTORY_VIEWS . DIRECTORY_SEPARATOR, $view->getViewPath());
	}

	public function testGetBlockList_FullList() {
		$view = new View;

		$view->addBlock('scripts', $view->js('jquery'));

		$this->assertGreaterThan(0, count($view->getBlockList()));
	}

	public function testGetBlock_EmptyBlock() {
		$view = new View;

		$this->assertTrue(is_null($view->getBlock('missing-block')));
	}

	public function providerViewWithVariables() {
		$name = 'Victor Cherubini';
		$age = 25;

		$human = new \stdClass;
		$human->name = $name;
		$human->age = $age;

		return array(
			array('list', array('list' => array('one', 'two', 'three'))),
			array('object', array('human' => $human)),
			array('replace-one', array('name' => $name)),
			array('replace-two', array('name' => $name, 'age' => $age)),
			array('welcome', array())
		);
	}

	private function loadRenderedView($view) {
		$path = DIRECTORY_VIEWS . DS . $view . '-rendered' . View::EXT;
		if ( !is_file($path) ) {
			return NULL;
		}

		$renderedView = file_get_contents($path);
		return $renderedView;
	}


	public function providerUnsafeString() {
		return array(
			array('<a href="http://google.com">Google</a>', '&lt;a href=&quot;http://google.com&quot;&gt;Google&lt;/a&gt;'),
			array('\'\';!--"<XSS>=&{()}', '\'\';!--&quot;&lt;XSS&gt;=&amp;{()}'),
			array('<SCRIPT SRC=http://ha.ckers.org/xss.js></SCRIPT>', '&lt;SCRIPT SRC=http://ha.ckers.org/xss.js&gt;&lt;/SCRIPT&gt;'),
			array('<IMG SRC="javascript:alert(\'XSS\');">', '&lt;IMG SRC=&quot;javascript:alert(\'XSS\');&quot;&gt;'),
			array('<IMG SRC=javascript:alert(\'XSS\')>', '&lt;IMG SRC=javascript:alert(\'XSS\')&gt;'),
			array('<IMG SRC=javascript:alert(&quot;XSS&quot;)>', '&lt;IMG SRC=javascript:alert(&amp;quot;XSS&amp;quot;)&gt;')
		);

	}
}
