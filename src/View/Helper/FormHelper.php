<?php declare(strict_types=1);

namespace Markup\View\Helper;

use Cake\View\Helper\FormHelper as CoreFormHelper;
use Markup\Html\HtmlStringable;

class FormHelper extends CoreFormHelper {

	/**
	 * @param string|\Markup\Html\HtmlStringable $title
	 * @param array|string|null $url
	 * @param array $options
	 *
	 * @return string
	 */
	public function postLink(string|HtmlStringable $title, array|string|null $url = null, array $options = []): string {
		if ($title instanceof HtmlStringable) {
			$options['escapeTitle'] = false;
			$title = (string)$title;
		}

		return parent::postLink($title, $url, $options);
	}

	/**
	 * @param string|\Markup\Html\HtmlStringable $title
	 * @param array|string $url
	 * @param array $options
	 *
	 * @return string
	 */
	public function postButton(string|HtmlStringable $title, array|string $url, array $options = []): string {
		if ($title instanceof HtmlStringable) {
			$options['escapeTitle'] = false;
			$title = (string)$title;
		}

		return parent::postButton($title, $url, $options);
	}

}
