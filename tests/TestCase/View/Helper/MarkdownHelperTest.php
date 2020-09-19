<?php

namespace Markup\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Markup\View\Helper\MarkdownHelper;

class MarkdownHelperTest extends TestCase {

	/**
	 * @var \Markup\View\Helper\MarkdownHelper
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

		Configure::write('Markdown', [
				'debug' => false,
			]
		);

		$this->request = new ServerRequest();
		$view = new View($this->request);
		$this->helper = new MarkdownHelper($view);
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
Some **bold** text.
TEXT;

		$result = $this->helper->convert($text);
		$expected = '<p>Some <strong>bold</strong> text.</p>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testConvertQuote() {
		$text = <<<'TEXT'
Some

> quoted <b>text</b>
>
> -- Benjamin Franklin

text.
TEXT;

		$result = $this->helper->convert($text);
		$expected = <<<TXT
<p>Some</p>
<blockquote>
<p>quoted &lt;b&gt;text&lt;/b&gt;</p>
<p>-- Benjamin Franklin</p>
</blockquote>
<p>text.</p>
TXT;
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testConvertDebug() {
		$this->helper->setConfig('debug', true);

		$text = <<<'TEXT'
Some **bold** text.
TEXT;

		$result = $this->helper->convert($text);
		$expected = '<!-- ';
		$this->assertStringContainsString($expected, $result);
		$expected = 'ms -->';
		$this->assertStringContainsString($expected, $result);
	}

}
