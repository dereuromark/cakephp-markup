<?php

namespace Markup\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Markup\View\Helper\BbcodeHelper;

class BbcodeHelperTest extends TestCase {

	/**
	 * @var \Markup\View\Helper\BbcodeHelper
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

		Configure::write('Bbcode', [
				'debug' => false,
			]
		);

		$this->request = new ServerRequest();
		$view = new View($this->request);
		$this->helper = new BbcodeHelper($view);
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
Some [b]bold[/b] text.
TEXT;

		$result = $this->helper->convert($text);
		$expected = 'Some <b>bold</b> text.';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testConvertDebug() {
		$this->helper->setConfig('debug', true);

		$text = <<<'TEXT'
Some [b]bold[/b] text.
TEXT;

		$result = $this->helper->convert($text);
		$expected = '<!-- ';
		$this->assertStringContainsString($expected, $result);
		$expected = 'ms -->';
		$this->assertStringContainsString($expected, $result);
	}

}
