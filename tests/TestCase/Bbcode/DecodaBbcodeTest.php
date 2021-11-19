<?php

namespace Markup\Test\TestCase\Bbcode;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Markup\Bbcode\DecodaBbcode;

class DecodaBbcodeTest extends TestCase {

	/**
	 * @var \Markup\Bbcode\DecodaBbcode
	 */
	protected $bbcode;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('Bbcode', [
				'debug' => false,
			]);

		$this->bbcode = new DecodaBbcode();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->bbcode);
	}

	/**
	 * @return void
	 */
	public function testConvertText(): void {
		$text = <<<'TEXT'
[h1]Header[/h1]

My [b]bold[/b] text with [code]code[/code] and more "<b>demo</b>".

A paragraph [url=http://example.com]with links[/url] and [abbr=xxx]yyy[/abbr].


Another paragraph.

[h2]Header 2[/h2]

Foo bar.
TEXT;

		$result = $this->bbcode->convert($text);
		$expected = '<h1>Header</h1><br><br>My <b>bold</b> text with <pre class="decoda-code"><code>code</code></pre> and more "&lt;b&gt;demo&lt;/b&gt;".'
			. '<br><br>A paragraph with links and <abbr>yyy</abbr>.<br><br><br>Another paragraph.<br><br>'
			. '<h2>Header 2</h2><br><br>Foo bar.';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testConvertHtml(): void {
		$text = <<<'TEXT'
[h1]Header[/h1]

My [b]bold[/b] text with <b>manual bold</b> markup.
TEXT;

		$result = $this->bbcode->convert($text, ['escape' => false]);
		$expected = '<h1>Header</h1><br><br>My <b>bold</b> text with <b>manual bold</b> markup.';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testConvertVideo(): void {
		$text = <<<'TEXT'
Video Demo
[video=youtube]123[/video]
Enjoy!
TEXT;

		$result = $this->bbcode->convert($text, ['escape' => false]);
		$expected = 'Video Demo<br><iframe src="//www.youtube.com/embed/123?wmode=transparent" type="text/html" width="100%" height="295" frameborder="0" allowfullscreen></iframe><br>Enjoy!';
		$this->assertSame($expected, $result);
	}

}
