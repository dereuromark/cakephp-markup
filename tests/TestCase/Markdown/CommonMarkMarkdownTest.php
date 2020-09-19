<?php

namespace Highlighter\Test\TestCase\Markdown;

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
			]
		);

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

}
