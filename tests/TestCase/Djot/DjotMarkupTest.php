<?php

namespace Markup\Test\TestCase\Djot;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Markup\Djot\DjotMarkup;

class DjotMarkupTest extends TestCase {

	/**
	 * @var \Markup\Djot\DjotMarkup
	 */
	protected $djot;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('Djot', [
			'debug' => false,
		]);

		$this->djot = new DjotMarkup();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->djot);
	}

	/**
	 * @return void
	 */
	public function testConvertText(): void {
		$text = <<<'TEXT'
# Header

My *bold* text with `code` and more.

A paragraph [with links](http://example.com).

## Header 2

Foo bar.
TEXT;

		$result = $this->djot->convert($text);
		$this->assertStringContainsString('<h1>Header</h1>', $result);
		$this->assertStringContainsString('<strong>bold</strong>', $result);
		$this->assertStringContainsString('<code>code</code>', $result);
		$this->assertStringContainsString('<a href="http://example.com">with links</a>', $result);
		$this->assertStringContainsString('<h2>Header 2</h2>', $result);
		$this->assertStringContainsString('<p>Foo bar.</p>', $result);
	}

	/**
	 * @return void
	 */
	public function testConvertSafeMode(): void {
		$text = <<<'TEXT'
# Header

A link with [dangerous URL](javascript:alert('xss')).
TEXT;

		$result = $this->djot->convert($text, ['safeMode' => true]);
		$this->assertStringContainsString('<h1>Header</h1>', $result);
		// Safe mode should sanitize dangerous URLs
		$this->assertStringNotContainsString('javascript:', $result);
	}

	/**
	 * @return void
	 */
	public function testConvertWithoutSafeMode(): void {
		$text = <<<'TEXT'
# Header

A link with [dangerous URL](javascript:alert('xss')).
TEXT;

		$result = $this->djot->convert($text, ['safeMode' => false]);
		$this->assertStringContainsString('<h1>Header</h1>', $result);
		// Without safe mode, dangerous URLs are allowed
		$this->assertStringContainsString('javascript:', $result);
	}

	/**
	 * @return void
	 */
	public function testConvertRawHtml(): void {
		$text = <<<'TEXT'
# Header

Some text with `<b>raw html</b>`{=html} inline.
TEXT;

		$result = $this->djot->convert($text, ['safeMode' => false]);
		$this->assertStringContainsString('<h1>Header</h1>', $result);
		$this->assertStringContainsString('<b>raw html</b>', $result);
	}

}
