<?php

namespace Markup\Test\TestCase\View;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Markup\Carve\CarveMarkup;
use Markup\View\CarveView;
use ReflectionClass;

class CarveViewTest extends TestCase {

	/**
	 * @var \Markup\View\CarveView
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

		$this->testTemplatePath = TMP . 'carve_test' . DS;
		if (!is_dir($this->testTemplatePath)) {
			mkdir($this->testTemplatePath, 0777, true);
		}

		Configure::write('App.paths.templates', [$this->testTemplatePath]);
		Configure::delete('Carve');

		$this->view = new CarveView();
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
	public function testRenderBasicCarve(): void {
		$templateContent = <<<'CARVE'
# Hello World

This is *bold* and `code`.
CARVE;
		file_put_contents($this->testTemplatePath . 'test.carve', $templateContent);

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
		$templateContent = <<<'CARVE'
# Welcome, {{username}}!

Your email is {{email}}.
CARVE;
		file_put_contents($this->testTemplatePath . 'vars.carve', $templateContent);

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
		$templateContent = <<<'CARVE'
# Test

A [dangerous link](javascript:alert('xss')).
CARVE;
		file_put_contents($this->testTemplatePath . 'safe.carve', $templateContent);

		$this->view->setConfig('safeMode', true);
		$this->view->setTemplatePath('');
		$this->view->setTemplate('safe');
		$result = $this->view->render();

		$this->assertStringNotContainsString('javascript:', $result);
	}

	/**
	 * Global `Carve` Configure values must apply to direct `.carve` rendering.
	 *
	 * @return void
	 */
	public function testHonorsGlobalConfig(): void {
		Configure::write('Carve', ['safeMode' => false]);

		$view = new CarveView();
		$this->assertFalse($view->getConfig('safeMode'));
	}

	/**
	 * @return void
	 */
	public function testFileExtension(): void {
		$view = new CarveView();
		$reflection = new ReflectionClass($view);
		$property = $reflection->getProperty('_ext');

		$this->assertSame('.carve', $property->getValue($view));
	}

	/**
	 * @return void
	 */
	public function testDefaultConfig(): void {
		$view = new CarveView();

		$this->assertTrue($view->getConfig('safeMode'));
		$this->assertSame(CarveMarkup::class, $view->getConfig('converter'));
	}

}
