<?php

namespace Markup\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Markup\View\Helper\DjotHelper;

class DjotHelperTest extends TestCase {

	/**
	 * @var \Markup\View\Helper\DjotHelper
	 */
	protected $helper;

	/**
	 * @var \Cake\Http\ServerRequest
	 */
	protected $request;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('Djot', [
			'debug' => false,
		]);

		$this->request = new ServerRequest();
		$view = new View($this->request);
		$this->helper = new DjotHelper($view);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->helper);
	}

	/**
	 * @return void
	 */
	public function testConvert() {
		$text = <<<'TEXT'
Some *bold* text.
TEXT;

		$result = $this->helper->convert($text);
		$this->assertStringContainsString('<strong>bold</strong>', $result);
	}

	/**
	 * @return void
	 */
	public function testConvertQuote() {
		$text = <<<'TEXT'
Some

> quoted text
>
> -- Benjamin Franklin

text.
TEXT;

		$result = $this->helper->convert($text);
		$this->assertStringContainsString('<blockquote>', $result);
		$this->assertStringContainsString('quoted text', $result);
	}

	/**
	 * @return void
	 */
	public function testConvertDebug() {
		$this->helper->setConfig('debug', true);

		$text = <<<'TEXT'
Some *bold* text.
TEXT;

		$result = $this->helper->convert($text);
		$expected = '<!-- ';
		$this->assertStringContainsString($expected, $result);
		$expected = 'ms -->';
		$this->assertStringContainsString($expected, $result);
	}

}
