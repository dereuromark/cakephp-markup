<?php

namespace Markup\View;

use Cake\View\View;
use Markup\Carve\CarveInterface;
use Markup\Carve\CarveMarkup;

/**
 * CarveView allows rendering `.carve` template files directly.
 *
 * Carve is a post-Markdown lightweight markup language with visual mnemonics and
 * human-centered design. This view class lets you use `.carve` files as templates
 * that get converted to HTML.
 *
 * ## Usage
 *
 * In your controller:
 * ```php
 * public function viewClasses(): array
 * {
 *     return [CarveView::class];
 * }
 *
 * public function documentation(): void
 * {
 *     $this->viewBuilder()->setClassName('Markup.Carve');
 * }
 * ```
 *
 * Then create templates with `.carve` extension:
 * `templates/Pages/documentation.carve`
 *
 * ## Variable Substitution
 *
 * View variables are available for simple substitution using `{{varName}}` syntax:
 * ```carve
 * # Welcome, {{username}}!
 *
 * Your account was created on {{createdAt}}.
 * ```
 *
 * ## Configuration
 *
 * Configure via `Configure::write('Carve', [...])` or pass options to the view:
 * - `safeMode`: Enable XSS protection (default: true)
 * - `converter`: Custom converter class implementing CarveInterface
 */
class CarveView extends View {

	/**
	 * File extension for Carve templates.
	 *
	 * @var string
	 */
	protected string $_ext = '.carve';

	/**
	 * @var \Markup\Carve\CarveInterface|null
	 */
	protected ?CarveInterface $_converter = null;

	/**
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [
		'safeMode' => true,
		'converter' => CarveMarkup::class,
	];

	/**
	 * Renders a Carve template file and converts it to HTML.
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
	 * Get the Carve converter instance.
	 *
	 * @return \Markup\Carve\CarveInterface
	 */
	protected function _getConverter(): CarveInterface {
		if ($this->_converter !== null) {
			return $this->_converter;
		}

		/** @var class-string<\Markup\Carve\CarveInterface> $className */
		$className = $this->getConfig('converter') ?? CarveMarkup::class;
		$this->_converter = new $className($this->getConfig());

		return $this->_converter;
	}

}
