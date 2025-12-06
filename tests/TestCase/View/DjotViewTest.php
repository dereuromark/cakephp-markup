<?php

namespace Markup\Test\TestCase\View;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Markup\Djot\DjotMarkup;
use Markup\View\DjotView;
use ReflectionClass;

class DjotViewTest extends TestCase {

	/**
	 * @var \Markup\View\DjotView
	 */
	protected $view;

	/**
	 * @var string
	 */
	protected $testTemplatePath;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->testTemplatePath = TMP . 'djot_test' . DS;
		if (!is_dir($this->testTemplatePath)) {
			mkdir($this->testTemplatePath, 0777, true);
		}

		Configure::write('App.paths.templates', [$this->testTemplatePath]);

		$this->view = new DjotView();
		$this->view->disableAutoLayout();
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		// Clean up test templates
		$files = glob($this->testTemplatePath . '*');
		if ($files) {
			foreach ($files as $file) {
				unlink($file);
			}
		}
		if (is_dir($this->testTemplatePath)) {
			rmdir($this->testTemplatePath);
		}

		unset($this->view);
	}

	/**
	 * @return void
	 */
	public function testRenderBasicDjot(): void {
		$templateContent = <<<'DJOT'
# Hello World

This is *bold* and `code`.
DJOT;
		file_put_contents($this->testTemplatePath . 'test.djot', $templateContent);

		$this->view->setTemplatePath('');
		$this->view->setTemplate('test');
		$result = $this->view->render();

		$this->assertStringContainsString('<h1>Hello World</h1>', $result);
		$this->assertStringContainsString('<strong>bold</strong>', $result);
		$this->assertStringContainsString('<code>code</code>', $result);
	}

	/**
	 * @return void
	 */
	public function testRenderWithVariableSubstitution(): void {
		$templateContent = <<<'DJOT'
# Welcome, {{username}}!

Your email is {{email}}.
DJOT;
		file_put_contents($this->testTemplatePath . 'vars.djot', $templateContent);

		$this->view->setTemplatePath('');
		$this->view->setTemplate('vars');
		$this->view->set('username', 'John');
		$this->view->set('email', 'john@example.com');
		$result = $this->view->render();

		$this->assertStringContainsString('Welcome, John!', $result);
		$this->assertStringContainsString('john@example.com', $result);
	}

	/**
	 * @return void
	 */
	public function testSafeModeEnabled(): void {
		$templateContent = <<<'DJOT'
# Test

A [dangerous link](javascript:alert('xss')).
DJOT;
		file_put_contents($this->testTemplatePath . 'safe.djot', $templateContent);

		$this->view->setConfig('safeMode', true);
		$this->view->setTemplatePath('');
		$this->view->setTemplate('safe');
		$result = $this->view->render();

		$this->assertStringNotContainsString('javascript:', $result);
	}

	/**
	 * @return void
	 */
	public function testFileExtension(): void {
		$view = new DjotView();
		$reflection = new ReflectionClass($view);
		$property = $reflection->getProperty('_ext');

		$this->assertSame('.djot', $property->getValue($view));
	}

	/**
	 * @return void
	 */
	public function testDefaultConfig(): void {
		$view = new DjotView();

		$this->assertTrue($view->getConfig('safeMode'));
		$this->assertSame(DjotMarkup::class, $view->getConfig('converter'));
	}

}
