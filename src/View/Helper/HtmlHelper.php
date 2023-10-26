<?php declare(strict_types=1);

namespace Markup\View\Helper;

use Cake\View\Helper\HtmlHelper as CoreHtmlHelper;
use Markup\Html\HtmlStringable;

class HtmlHelper extends CoreHtmlHelper {

	/**
	 * @param \Markup\Html\HtmlStringable|array|string $title
	 * @param array|string|null $url
	 * @param array $options
	 *
	 * @return string
	 */
	public function link(array|string|HtmlStringable $title, array|string|null $url = null, array $options = []): string {
		if ($title instanceof HtmlStringable) {
			$options['escapeTitle'] = false;
			$title = (string)$title;
		}

		return parent::link($title, $url, $options);
	}

	public function linkFromPath(string|HtmlStringable $title, string $path, array $params = [], array $options = []): string {
		if ($title instanceof HtmlStringable) {
			$options['escapeTitle'] = false;
			$title = (string)$title;
		}

		return parent::linkFromPath($title, $path, $params, $options);
	}

}
