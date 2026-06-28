<?php

namespace Markup\View;

use Cake\Core\Configure;
use Cake\Event\EventManagerInterface;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\View\View;
use Markup\Djot\DjotInterface;
use Markup\Djot\DjotMarkup;

/**
 * DjotView allows rendering `.djot` template files directly.
 *
 * Djot is a modern markup language created by John MacFarlane (author of CommonMark/Pandoc).
 * This view class lets you use `.djot` files as templates that get converted to HTML.
 *
 * ## Usage
 *
 * In your controller:
 * ```php
 * public function viewClasses(): array
 * {
 *     return [DjotView::class];
 * }
 *
 * public function documentation(): void
 * {
 *     $this->viewBuilder()->setClassName('Markup.Djot');
 * }
 * ```
 *
 * Then create templates with `.djot` extension:
 * `templates/Pages/documentation.djot`
 *
 * ## Variable Substitution
 *
 * View variables are available for simple substitution using `{{varName}}` syntax:
 * ```djot
 * # Welcome, {{username}}!
 *
 * Your account was created on {{createdAt}}.
 * ```
 *
 * ## Configuration
 *
 * Configure via `Configure::write('Djot', [...])` or pass options to the view:
 * - `safeMode`: Enable XSS protection (default: true)
 * - `converter`: Custom converter class implementing DjotInterface
 */
class DjotView extends View {

	/**
	 * File extension for djot templates.
	 *
	 * @var string
	 */
	protected string $_ext = '.djot';

	/**
	 * @var \Markup\Djot\DjotInterface|null
	 */
	protected ?DjotInterface $_converter = null;

	/**
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [
		'safeMode' => true,
		'converter' => DjotMarkup::class,
	];

	/**
	 * Constructor.
	 *
	 * Merges the global `Djot` Configure values as defaults so that app-level
	 * settings (custom `converter`, `profile`, `safeMode`, ...) apply when
	 * rendering `.djot` templates. Explicit view options still win.
	 *
	 * @param \Cake\Http\ServerRequest|null $request Request instance.
	 * @param \Cake\Http\Response|null $response Response instance.
	 * @param \Cake\Event\EventManagerInterface|null $eventManager Event manager instance.
	 * @param array<string, mixed> $viewOptions View options.
	 */
	public function __construct(
		?ServerRequest $request = null,
		?Response $response = null,
		?EventManagerInterface $eventManager = null,
		array $viewOptions = [],
	) {
		$defaults = (array)Configure::read('Djot');
		parent::__construct($request, $response, $eventManager, $viewOptions + $defaults);
	}

	/**
	 * Renders a djot template file and converts it to HTML.
	 *
	 * @param string $templateFile The template file path.
	 * @param array<string, mixed> $dataForView View variables.
	 * @return string Rendered HTML output.
	 */
	protected function _evaluate(string $templateFile, array $dataForView): string {
		$content = file_get_contents($templateFile);
		if ($content === false) {
			return '';
		}

		// Simple variable substitution using {{varName}} syntax
		foreach ($dataForView as $key => $value) {
			if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
				$content = str_replace('{{' . $key . '}}', (string)$value, $content);
			}
		}

		return $this->_getConverter()->convert($content);
	}

	/**
	 * Get the djot converter instance.
	 *
	 * @return \Markup\Djot\DjotInterface
	 */
	protected function _getConverter(): DjotInterface {
		if ($this->_converter !== null) {
			return $this->_converter;
		}

		/** @var class-string<\Markup\Djot\DjotInterface> $className */
		$className = $this->getConfig('converter') ?? DjotMarkup::class;
		$this->_converter = new $className($this->getConfig());

		return $this->_converter;
	}

}
