<?php

namespace Markup\Test\TestCase\Highlighter;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Markup\Highlighter\JsHighlighter;

class JsHighlighterTest extends TestCase {

	/**
	 * @var \Markup\Highlighter\JsHighlighter
	 */
	protected $highlighter;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('Highlighter', [
				'debug' => false,
			]
		);

		$this->highlighter = new JsHighlighter();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->highlighter);
	}

	/**
	 * @return void
	 */
	public function testConvert(): void {
		$result = $this->highlighter->highlight('My text');
		$expected = '<pre><code class="language-txt">My text</code></pre>';
		$this->assertSame($expected, $result);
	}

}
