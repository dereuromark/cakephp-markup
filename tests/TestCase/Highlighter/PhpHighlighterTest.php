<?php

namespace Markup\Test\TestCase\Highlighter;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Markup\Highlighter\PhpHighlighter;

class PhpHighlighterTest extends TestCase {

	/**
	 * @var \Markup\Highlighter\PhpHighlighter
	 */
	protected $highlighter;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('Highlighter', [
				'debug' => false,
		]);

		$this->highlighter = new PhpHighlighter();
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
		$expected = '<pre class="language-txt"><code><span style="color: #000000">My&nbsp;text</span></code></pre>';
		if (version_compare(phpversion(), '8.3', '>=')) {
			$expected = '<div class="language-txt"><pre><code style="color: #000000">My text</code></pre></div>';
		}

		$this->assertSame($expected, $result);
	}

}
