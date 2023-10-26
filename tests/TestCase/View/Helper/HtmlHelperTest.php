<?php declare(strict_types=1);

namespace Markup\Test\TestCase\View\Helper;

use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Markup\Html\HtmlStringable;
use Markup\View\Helper\HtmlHelper;

class HtmlHelperTest extends TestCase {

	/**
	 * @var \Markup\View\Helper\HtmlHelper
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

		$this->request = new ServerRequest();
		$view = new View($this->request);
		$this->helper = new HtmlHelper($view);
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
	public function testLink() {
		$icon = new class implements HtmlStringable {

			/**
			 * @return string
			 */
			public function __toString(): string {
				return '<span>Some ICON HTML</span>';
			}
		};

		$result = $this->helper->link($icon, '/');
		$expected = '<a href="/"><span>Some ICON HTML</span></a>';
		$this->assertSame($expected, $result);
	}

}
