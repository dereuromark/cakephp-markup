<?php
namespace Highlighter\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Markup\View\Helper\HighlighterHelper;

/**
 *
 */
class HighlighterHelperTest extends TestCase {

	/**
	 * @var \Markup\View\Helper\HighlighterHelper
	 */
	public $Highlighter;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		Configure::write('Highlighter', [
				'debug' => false,
			]
		);

		$this->request = $this->getMockBuilder('Cake\Network\Request')->getMock();
		$this->view = new View($this->request);
		$this->Highlighter = new HighlighterHelper($this->view);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Highlighter);
	}

	/**
	 * @return void
	 */
	public function testHighlight() {
		$text = <<<'TEXT'
$key = 'string' . $this->something->do(true); // Some comment
TEXT;

		$result = $this->Highlighter->highlight($text, ['lang' => 'php']);
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

		$result = $this->Highlighter->highlight($text, ['lang' => 'php', 'prefix' => 'l-']);
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

		$this->Highlighter->config('highlighter', 'Markup\Highlighter\JsHighlighter');

		$result = $this->Highlighter->highlight($text, ['lang' => 'php']);
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

		$this->Highlighter->config('highlighter', 'Markup\Highlighter\JsHighlighter');
		$this->Highlighter->config('escape', false);

		$result = $this->Highlighter->highlight($text, ['lang' => 'php']);
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

		$this->Highlighter->config('highlighter', 'Markup\Highlighter\JsHighlighter');

		$result = $this->Highlighter->highlight($text, ['lang' => 'php', 'escape' => false]);
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

		$result = $this->Highlighter->highlight($text, ['lang' => 'php']);
		$expected = '<pre class="language-php"><code><span style="color: #000000">if&nbsp;($foo)&nbsp;{<br />&nbsp;&nbsp;&nbsp;&nbsp;while&nbsp;(true)&nbsp;{<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this-&gt;doSth();<br />&nbsp;&nbsp;&nbsp;&nbsp;}<br />}</span></code></pre>';
		$this->assertEquals($expected, $result);

		$this->Highlighter->config('highlighter', 'Markup\Highlighter\JsHighlighter');

		$result = $this->Highlighter->highlight($text, ['lang' => 'php']);
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

		$this->Highlighter->config('highlighter', 'Markup\Highlighter\JsHighlighter');

		$result = $this->Highlighter->highlight($text, ['lang' => 'php']);
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

		$this->Highlighter->config('debug', true);

		$result = $this->Highlighter->highlight($text, ['lang' => 'markdown']);
		$expected = '<!-- ';
		$this->assertContains($expected, $result);
		$expected = 'ms -->';
		$this->assertContains($expected, $result);
	}

}
