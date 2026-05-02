<?php

namespace Markup\Test\TestCase\Markdown;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Markup\Markdown\CommonMarkMarkdown;

class CommonMarkMarkdownTest extends TestCase {

	/**
	 * @var \Markup\Markdown\CommonMarkMarkdown
	 */
	protected $markdown;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('Markdown', [
			'debug' => false,
		]);

		$this->markdown = new CommonMarkMarkdown();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->markdown);
	}

	/**
	 * @return void
	 */
	public function testConvertText(): void {
		$text = <<<'TEXT'
# Header

My **bold** text with `code` and more "<b>demo</b>".

A paragraph [with links](http://example.com).

## Header 2

Foo bar.
TEXT;

		$result = $this->markdown->convert($text);
		$expected = <<<TXT
<h1>Header</h1>
<p>My <strong>bold</strong> text with <code>code</code> and more &quot;&lt;b&gt;demo&lt;/b&gt;&quot;.</p>
<p>A paragraph <a href="http://example.com">with links</a>.</p>
<h2>Header 2</h2>
<p>Foo bar.</p>

TXT;
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testConvertHtml(): void {
		$text = <<<'TEXT'
# Header

My **bold** text with <b>manual bold</b> markup.
TEXT;

		$result = $this->markdown->convert($text, ['escape' => false]);
		$expected = <<<TXT
<h1>Header</h1>
<p>My <strong>bold</strong> text with <b>manual bold</b> markup.</p>

TXT;
		$this->assertSame($expected, $result);
	}

	/**
	 * Defaults must neutralize the `javascript:` URL scheme on links and images
	 * so `[click](javascript:alert(1))` cannot escape into a usable href.
	 *
	 * @return void
	 */
	public function testConvertNeutralizesJavascriptLinks(): void {
		$text = "[click](javascript:alert(1))\n\n![x](javascript:alert(1))";

		$result = $this->markdown->convert($text);
		$this->assertStringNotContainsString('javascript:', $result);
	}

	/**
	 * Defaults must neutralize `data:` URLs which can carry inline HTML/JS.
	 *
	 * @return void
	 */
	public function testConvertNeutralizesDataLinks(): void {
		$text = '[click](data:text/html,<script>alert(1)</script>)';

		$result = $this->markdown->convert($text);
		$this->assertStringNotContainsString('data:text/html', $result);
		$this->assertStringNotContainsString('<script', $result);
	}

	/**
	 * The converter must be rebuilt when per-call options differ from the
	 * cached one. Otherwise the first call's safety setting would be pinned
	 * for the lifetime of the instance — a real XSS regression vector under
	 * long-lived FPM/queue workers.
	 *
	 * @return void
	 */
	public function testConverterRebuildsWhenOptionsChange(): void {
		$first = $this->markdown->convert('<b>x</b>', ['escape' => true]);
		$this->assertStringContainsString('&lt;b&gt;', $first);

		$second = $this->markdown->convert('<b>x</b>', ['escape' => false]);
		$this->assertStringContainsString('<b>x</b>', $second);
		$this->assertStringNotContainsString('&lt;b&gt;', $second);
	}

}
