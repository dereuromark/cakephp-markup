<?php

namespace Markup\Test\TestCase\Carve;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Markup\Carve\CarveMarkup;

class CarveMarkupTest extends TestCase {

	/**
	 * @var \Markup\Carve\CarveMarkup
	 */
	protected $carve;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('Carve', [
			'debug' => false,
		]);

		$this->carve = new CarveMarkup();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->carve);
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

		$result = $this->carve->convert($text);
		$this->assertStringContainsString('<h1>Header</h1>', $result);
		$this->assertStringContainsString('<strong>bold</strong>', $result);
		$this->assertStringContainsString('<code>code</code>', $result);
		$this->assertStringContainsString('<a href="http://example.com">with links</a>', $result);
		$this->assertStringContainsString('<h2>Header 2</h2>', $result);
		$this->assertStringContainsString('Foo bar.', $result);
	}

	/**
	 * @return void
	 */
	public function testConvertEmphasis(): void {
		// Carve uses slashes as a visual mnemonic for emphasis (italic).
		$result = $this->carve->convert('Some /italic/ text.');
		$this->assertStringContainsString('<em>italic</em>', $result);
	}

	/**
	 * @return void
	 */
	public function testConvertSafeMode(): void {
		$text = <<<'TEXT'
# Header

A link with [dangerous URL](javascript:alert('xss')).
TEXT;

		$result = $this->carve->convert($text, ['safeMode' => true]);
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

Some text with `<b>raw html</b>`{=html} inline.

A link with [dangerous URL](javascript:alert('xss')).
TEXT;

		$result = $this->carve->convert($text, ['safeMode' => false]);
		$this->assertStringContainsString('<h1>Header</h1>', $result);
		// Without safe mode, raw HTML is passed through unescaped.
		$this->assertStringContainsString('<b>raw html</b>', $result);
		// Dangerous URL schemes are stripped by the library's always-on
		// baseline hardening, independent of safe mode.
		$this->assertStringNotContainsString('javascript:', $result);
	}

	/**
	 * The converter must be rebuilt when per-call options differ from the
	 * cached one. Otherwise the first call's safeMode would be pinned for the
	 * lifetime of the instance — a real XSS regression vector under long-lived
	 * FPM/queue workers.
	 *
	 * @return void
	 */
	public function testConverterRebuildsWhenOptionsChange(): void {
		$text = 'Some `<b>raw html</b>`{=html} inline.';

		// Without safe mode raw HTML passes through unescaped.
		$first = $this->carve->convert($text, ['safeMode' => false]);
		$this->assertStringContainsString('<b>raw html</b>', $first);

		// A later call with safe mode must rebuild the converter and escape it.
		$second = $this->carve->convert($text, ['safeMode' => true]);
		$this->assertStringNotContainsString('<b>raw html</b>', $second);
		$this->assertStringContainsString('&lt;b&gt;', $second);
	}

	/**
	 * @return void
	 */
	public function testConvertRawHtml(): void {
		$text = <<<'TEXT'
# Header

Some text with `<b>raw html</b>`{=html} inline.
TEXT;

		$result = $this->carve->convert($text, ['safeMode' => false]);
		$this->assertStringContainsString('<h1>Header</h1>', $result);
		$this->assertStringContainsString('<b>raw html</b>', $result);
	}

}
