<?php
namespace Highlighter\Test\TestCase\View\Helper;

use Markup\View\Helper\HighlighterHelper;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;

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

		$this->request = $this->getMock('Cake\Network\Request', []);
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
$key = $this->request->query('key');
if (is_array($key)) { // Or: if (!is_scalar($key))
	throw new NotFoundException('Invalid query string'); // Simple 404
}
$result = 'string' . $this->request->query('key'); // Dangerous without checking if a stringish (=scalar) value
TEXT;

		$result = $this->Highlighter->highlight($text, 'php');
		debug($result);
	}

}
