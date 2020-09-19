<?php

namespace Markup\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Markup\View\Helper\HighlighterHelper;

class HighlighterHelperTest extends TestCase {

	/**
	 * @var \Markup\View\Helper\HighlighterHelper
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

		Configure::write('Highlighter', [
				'debug' => false,
			]
		);

		$this->request = new ServerRequest();
		$view = new View($this->request);
		$this->helper = new HighlighterHelper($view);
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
	public function testHighlight() {
		$text = <<<'TEXT'
$key = 'string' . $this->something->do(true); // Some comment
TEXT;

		$result = $this->helper->highlight($text, ['lang' => 'php']);
		$expected = '<pre class="language-php"><code><span style="color: #000000">$key&nbsp;=&nbsp;\'string\'&nbsp;.&nbsp;$this-&gt;something-&gt;do(true);&nbsp;//&nbsp;Some&nbsp;comment</span></code></pre>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testHighlightPrefix() {
		$text = <<<'TEXT'
$key = 'string' . $this->something->do(true);
TEXT;

		$result = $this->helper->highlight($text, ['lang' => 'php', 'prefix' => 'l-']);
		$expected = '<pre class="l-php"><code><span style="color: #000000">$key&nbsp;=&nbsp;\'string\'&nbsp;.&nbsp;$this-&gt;something-&gt;do(true);</span></code></pre>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testHighlightJs() {
		$text = <<<'TEXT'
$key = $this->request->query('key');
TEXT;

		$this->helper->setConfig('highlighter', 'Markup\Highlighter\JsHighlighter');

		$result = $this->helper->highlight($text, ['lang' => 'php']);
		$expected = '<pre><code class="language-php">$key = $this-&gt;request-&gt;query(&#039;key&#039;);</code></pre>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testHighlightJsEscapeFalse() {
		$text = <<<'TEXT'
$key = $this->request->query('key');
TEXT;

		$this->helper->setConfig('highlighter', 'Markup\Highlighter\JsHighlighter');
		$this->helper->setConfig('escape', false);

		$result = $this->helper->highlight($text, ['lang' => 'php']);
		$expected = '<pre><code class="language-php">$key = $this->request->query(\'key\');</code></pre>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testHighlightJsEscapeFalseOption() {
		$text = <<<'TEXT'
$key = $this->request->query('key');
TEXT;

		$this->helper->setConfig('highlighter', 'Markup\Highlighter\JsHighlighter');

		$result = $this->helper->highlight($text, ['lang' => 'php', 'escape' => false]);
		$expected = '<pre><code class="language-php">$key = $this->request->query(\'key\');</code></pre>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testHighlightIndentation() {
		$text = <<<'TEXT'
if ($foo) {
	while (true) {
		$this->doSth();
	}
}
TEXT;

		$result = $this->helper->highlight($text, ['lang' => 'php']);
		$expected = '<pre class="language-php"><code><span style="color: #000000">if&nbsp;($foo)&nbsp;{<br />&nbsp;&nbsp;&nbsp;&nbsp;while&nbsp;(true)&nbsp;{<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this-&gt;doSth();<br />&nbsp;&nbsp;&nbsp;&nbsp;}<br />}</span></code></pre>';
		$this->assertEquals($expected, $result);

		$this->helper->setConfig('highlighter', 'Markup\Highlighter\JsHighlighter');

		$result = $this->helper->highlight($text, ['lang' => 'php']);
		$expected = '<pre class="language-php"><code><span style="color: #000000">if&nbsp;($foo)&nbsp;{<br />&nbsp;&nbsp;&nbsp;&nbsp;while&nbsp;(true)&nbsp;{<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this-&gt;doSth();<br />&nbsp;&nbsp;&nbsp;&nbsp;}<br />}</span></code></pre>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testHighlightJsIndentation() {
		$text = <<<'TEXT'
if ($foo) {
	while (true) {
		$this->doSth();
	}
}
TEXT;

		$this->helper->setConfig('highlighter', 'Markup\Highlighter\JsHighlighter');

		$result = $this->helper->highlight($text, ['lang' => 'php']);
		$expected = '<pre><code class="language-php">if ($foo) {'
			. "\n" . '    while (true) {'
			. "\n" . '        $this-&gt;doSth();'
			. "\n" . '    }'
			. "\n" . '}</code></pre>';
		$this->assertTextEquals($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testHighlightDebug() {
		$text = <<<'TEXT'
# hello world

you can write text [with links](http://example.com) inline or [link references][1].

* one _thing_ has *em*phasis
* two __things__ are **bold**

[1]: http://example.com

---

hello world
===========

<this_is inline="xml"></this_is>

> markdown is so cool

	so are code segments

1. one thing (yeah!)
2. two thing `i can write code`, and `more` wipee!
TEXT;

		$this->helper->setConfig('debug', true);

		$result = $this->helper->highlight($text, ['lang' => 'markdown']);
		$expected = '<!-- ';
		$this->assertStringContainsString($expected, $result);
		$expected = 'ms -->';
		$this->assertStringContainsString($expected, $result);
	}

}
